<?php

namespace App\Filament\App\Resources\JournalEntryVoucherResource\Pages;

use App\Actions\Memberships\BuildAccountClosureDisclosure;
use App\Filament\App\Resources\JournalEntryVoucherResource;
use App\Models\Account;
use App\Models\JournalEntryVoucher;
use App\Models\Member;
use App\Models\TransactionType;
use Filament\Actions\CreateAction;
use Filament\Notifications\Notification;
use Filament\Resources\Pages\ManageRecords;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;

class ManageJournalEntryVouchers extends ManageRecords
{
    protected static string $resource = JournalEntryVoucherResource::class;

    public function mount(): void
    {
        parent::mount();
        data_set($this, 'tableFilters.transaction_date.transaction_date', (config('app.transaction_date')?->format('m/d/Y') ?? today()->format('m/d/Y')).' - '.(config('app.transaction_date')?->format('m/d/Y') ?? today()->format('m/d/Y')));
    }

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make()
                ->action(function ($data) {
                    $items = $this->resolveVoucherItems($data);
                    $accounts = Account::findMany(collect($items)->pluck('account_id')->unique()->toArray());
                    foreach ($items as $item) {
                        $account = $accounts->find($item['account_id']);
                        if (in_array($account->tag, ['member_regular_cbu_paid', 'member_preferred_cbu_paid', 'member_laboratory_cbu_paid'])
                            && ! $account->member->active_capital_subscription) {
                            Notification::make()->title('No Active Capital Subscription found for '.$account->member->full_name)->danger()->send();
                            throw ValidationException::withMessages([
                                'mountedActions.0.data.description' => 'Some members have no active capital subscription.',
                            ]);
                        }
                    }
                    DB::beginTransaction();
                    $transactionType = TransactionType::CDJ();
                    $data['voucher_type_id'] = 6;
                    unset(
                        $data['journal_entry_voucher_items'],
                        $data['compute_net'],
                        $data['action'],
                        $data['closure_member_id'],
                        $data['closure_disclosure'],
                    );
                    $data['transaction_date'] = config('app.transaction_date') ?? today();
                    $jev = JournalEntryVoucher::create($data);
                    foreach ($items as $item) {
                        unset($item['member_id']);
                        $jev->journal_entry_voucher_items()->create($item);
                    }
                    DB::commit();
                })
                ->createAnother(false),
        ];
    }

    /**
     * @param  array<string, mixed>  $data
     * @return array<int, array<string, mixed>>
     */
    private function resolveVoucherItems(array $data): array
    {
        if (($data['action'] ?? null) === 'closed_account') {
            $member = Member::find($data['closure_member_id'] ?? null);

            if (! $member) {
                throw ValidationException::withMessages([
                    'closure_member_id' => 'Select a member account to close.',
                ]);
            }

            return app(BuildAccountClosureDisclosure::class)->handle($member)['voucher_items'];
        }

        return collect($data['journal_entry_voucher_items'])
            ->map(function (array $item): array {
                unset($item['details']);

                return $item;
            })
            ->all();
    }
}
