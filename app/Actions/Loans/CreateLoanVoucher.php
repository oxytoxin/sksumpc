<?php

namespace App\Actions\Loans;

use App\Models\Account;
use App\Models\DisbursementVoucher;
use App\Models\JournalEntryVoucher;
use App\Models\Loan;
use App\Models\VoucherType;
use Illuminate\Support\Arr;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;
use Lorisleiva\Actions\Concerns\AsAction;

class CreateLoanVoucher
{
    use AsAction;

    /**
     * @param  array{
     *     name: string,
     *     address: string,
     *     reference_number: string,
     *     check_number?: string|null,
     *     voucher_number: string,
     *     description: string,
     *     voucher_items: array<int, array<string, mixed>>
     * }  $data
     */
    public function handle(Loan $loan, array $data): DisbursementVoucher|JournalEntryVoucher
    {
        return DB::transaction(function () use ($loan, $data): DisbursementVoucher|JournalEntryVoucher {
            $loan = Loan::query()->lockForUpdate()->findOrFail($loan->getKey());

            if ($loan->posted) {
                throw ValidationException::withMessages([
                    'voucher' => 'This loan has already been posted.',
                ]);
            }

            if ($loan->hasNegativeNetAmount()) {
                throw ValidationException::withMessages([
                    'voucher' => 'Loans with a negative net amount cannot be posted.',
                ]);
            }

            $items = collect($data['voucher_items']);
            unset($data['voucher_items']);

            /** @var \Illuminate\Support\Collection<int, Account> $accounts */
            $accounts = Account::withCode()->findMany($items->pluck('account_id')->unique());
            $disclosureSheetItems = $items->map(function (array $item) use ($accounts): array {
                $account = $accounts->first(
                    fn (Account $account): bool => $account->id === (int) $item['account_id']
                );

                if (! $account) {
                    throw ValidationException::withMessages([
                        'voucher_items' => 'One or more voucher accounts no longer exist.',
                    ]);
                }

                $item['name'] = (string) $account->getAttribute('code');

                return $item;
            })->all();

            $data['voucher_type_id'] = VoucherType::query()
                ->where('name', 'LOANS')
                ->value('id');
            $data['transaction_date'] = config('app.transaction_date') ?? today();

            if (! $data['voucher_type_id']) {
                throw ValidationException::withMessages([
                    'voucher' => 'The LOANS voucher type is not configured.',
                ]);
            }

            if ($loan->isZeroNetAmount()) {
                unset($data['check_number']);

                $cashInBankAccountId = Account::getCashInBankGF()?->id;
                $voucherItems = $items->reject(
                    fn (array $item): bool => $this->isZeroNetAmountItem($item, $cashInBankAccountId)
                );

                $this->ensureBalanced($voucherItems);

                $voucher = JournalEntryVoucher::create($data);
                $voucherItems->each(fn (array $item) => $voucher->journal_entry_voucher_items()->create($this->voucherItemData($item)));

                $loan->update([
                    'check_number' => null,
                    'disbursement_voucher_id' => null,
                    'journal_entry_voucher_id' => $voucher->id,
                    'disclosure_sheet_items' => $disclosureSheetItems,
                ]);
            } else {
                $this->ensureBalanced($items);

                $voucher = DisbursementVoucher::create($data);

                $items->each(fn (array $item) => $voucher->disbursement_voucher_items()->create($this->voucherItemData($item)));

                $loan->update([
                    'check_number' => $voucher->check_number,
                    'disbursement_voucher_id' => $voucher->id,
                    'journal_entry_voucher_id' => null,
                    'disclosure_sheet_items' => $disclosureSheetItems,
                ]);
            }

            app(ApproveLoanPosting::class)->handle($loan);

            return $voucher;
        });
    }

    /** @param array<string, mixed> $item */
    private function isZeroNetAmountItem(array $item, ?int $cashInBankAccountId): bool
    {
        if (($item['code'] ?? null) === 'net_amount') {
            return true;
        }

        return (int) $item['account_id'] === $cashInBankAccountId
            && (float) ($item['debit'] ?? 0) === 0.0
            && (float) ($item['credit'] ?? 0) === 0.0;
    }

    /**
     * @param  array<string, mixed>  $item
     * @return array<string, mixed>
     */
    private function voucherItemData(array $item): array
    {
        return Arr::only($item, [
            'account_id',
            'credit',
            'debit',
            'details',
        ]);
    }

    /** @param Collection<int, array<string, mixed>> $items */
    private function ensureBalanced(Collection $items): void
    {
        $debitTotal = $items->sum(fn (array $item): float => (float) ($item['debit'] ?? 0));
        $creditTotal = $items->sum(fn (array $item): float => (float) ($item['credit'] ?? 0));

        if (round($debitTotal, 2) !== round($creditTotal, 2)) {
            throw ValidationException::withMessages([
                'voucher_items' => 'Voucher items must balance without a Cash in Bank entry.',
            ]);
        }
    }
}
