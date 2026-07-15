<?php

use App\Actions\Loans\CreateLoanVoucher;
use App\Enums\PaymentTypes;
use App\Filament\App\Resources\LoanResource\Actions\ViewLoanDetailsActionGroup;
use App\Models\Account;
use App\Models\DisbursementVoucher;
use App\Models\JournalEntryVoucher;
use App\Models\Loan;
use App\Models\LoanApplication;
use App\Models\Transaction;
use App\Models\TransactionType;
use App\Models\User;
use Carbon\CarbonImmutable;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Validation\ValidationException;

use function Pest\Laravel\actingAs;

uses(DatabaseTransactions::class);

beforeEach(function () {
    actingAs(User::query()->firstOrFail());
    config()->set('app.transaction_date', CarbonImmutable::parse('2026-04-15'));

    $this->voucherAccounts = Account::query()
        ->whereNull('member_id')
        ->whereNull('tag')
        ->whereDoesntHave('children')
        ->orderBy('id')
        ->limit(2)
        ->get();

    expect($this->voucherAccounts)->toHaveCount(2);
});

/**
 * @param  array<int, array<string, mixed>>  $items
 */
function createLoanVoucherTestLoan(string $grossAmount, string $deductionsAmount, array $items): Loan
{
    $attributes = Loan::query()->latest('id')->firstOrFail()->getAttributes();

    unset(
        $attributes['id'],
        $attributes['net_amount'],
        $attributes['created_at'],
        $attributes['updated_at'],
    );

    $attributes['gross_amount'] = $grossAmount;
    $attributes['deductions_amount'] = $deductionsAmount;
    $attributes['disclosure_sheet_items'] = $items;
    $attributes['check_number'] = null;
    $attributes['disbursement_voucher_id'] = null;
    $attributes['journal_entry_voucher_id'] = null;
    $attributes['posted'] = false;

    $loan = Loan::withoutEvents(fn (): Loan => Loan::query()->create($attributes));
    $loan->loan_application()->update(['status' => LoanApplication::STATUS_APPROVED]);

    return $loan->refresh();
}

/**
 * @return array<int, array<string, mixed>>
 */
function loanVoucherTestItems(Account $debitAccount, Account $creditAccount, string $deduction, string $netAmount): array
{
    return [
        [
            'member_id' => null,
            'account_id' => $debitAccount->id,
            'debit' => '100.00',
            'credit' => null,
            'code' => 'principal',
        ],
        [
            'member_id' => null,
            'account_id' => $creditAccount->id,
            'debit' => null,
            'credit' => $deduction,
            'code' => 'deduction',
        ],
        [
            'member_id' => null,
            'account_id' => Account::getCashInBankGF()->id,
            'debit' => null,
            'credit' => $netAmount,
            'code' => 'net_amount',
            'readonly' => true,
        ],
    ];
}

/**
 * @param  array<int, array<string, mixed>>  $items
 * @return array<string, mixed>
 */
function loanVoucherTestData(string $referenceNumber, array $items): array
{
    return [
        'name' => 'Test Member',
        'address' => 'Test Address',
        'reference_number' => $referenceNumber,
        'check_number' => 'CHK-100',
        'voucher_number' => $referenceNumber,
        'description' => 'Test loan voucher',
        'voucher_items' => $items,
    ];
}

it('posts a zero-net loan as a journal entry voucher', function () {
    $items = loanVoucherTestItems(
        $this->voucherAccounts[0],
        $this->voucherAccounts[1],
        '100.00',
        '0.00',
    );
    $loan = createLoanVoucherTestLoan('100.00', '100.00', $items);
    $referenceNumber = 'EDITED JEV 2026-04-900';

    $voucher = app(CreateLoanVoucher::class)->handle(
        $loan,
        loanVoucherTestData($referenceNumber, $items),
    );

    expect($voucher)->toBeInstanceOf(JournalEntryVoucher::class)
        ->and($voucher->reference_number)->toBe($referenceNumber)
        ->and($voucher->voucher_number)->toBe($referenceNumber)
        ->and($voucher->journal_entry_voucher_items)->toHaveCount(2)
        ->and($voucher->journal_entry_voucher_items->pluck('account_id'))
        ->not->toContain(Account::getCashInBankGF()->id);

    $loan->refresh();

    expect($loan->posted)->toBeTrue()
        ->and($loan->journal_entry_voucher_id)->toBe($voucher->id)
        ->and($loan->disbursement_voucher_id)->toBeNull()
        ->and($loan->check_number)->toBeNull()
        ->and(collect($loan->disclosure_sheet_items)->firstWhere('code', 'net_amount')['credit'])->toBe('0.00')
        ->and($loan->loan_application->status)->toBe(LoanApplication::STATUS_POSTED);

    expect(Transaction::query()->where('reference_number', $referenceNumber)->count())->toBe(2)
        ->and(Transaction::query()
            ->where('reference_number', $referenceNumber)
            ->where('transaction_type_id', TransactionType::JEV()->id)
            ->where('payment_type_id', PaymentTypes::JEV->value)
            ->count())->toBe(2)
        ->and(DisbursementVoucher::query()->where('reference_number', $referenceNumber)->exists())->toBeFalse();
});

it('keeps posting a positive-net loan as a disbursement voucher', function () {
    $items = loanVoucherTestItems(
        $this->voucherAccounts[0],
        $this->voucherAccounts[1],
        '25.00',
        '75.00',
    );
    $loan = createLoanVoucherTestLoan('100.00', '25.00', $items);
    $referenceNumber = 'TEST DV 2026-04-901';

    $voucher = app(CreateLoanVoucher::class)->handle(
        $loan,
        loanVoucherTestData($referenceNumber, $items),
    );

    expect($voucher)->toBeInstanceOf(DisbursementVoucher::class)
        ->and($voucher->check_number)->toBe('CHK-100')
        ->and($voucher->disbursement_voucher_items)->toHaveCount(3);

    $loan->refresh();

    expect($loan->posted)->toBeTrue()
        ->and($loan->disbursement_voucher_id)->toBe($voucher->id)
        ->and($loan->journal_entry_voucher_id)->toBeNull()
        ->and($loan->check_number)->toBe('CHK-100')
        ->and($loan->loan_application->status)->toBe(LoanApplication::STATUS_POSTED);

    expect(Transaction::query()->where('reference_number', $referenceNumber)->count())->toBe(3)
        ->and(Transaction::query()
            ->where('reference_number', $referenceNumber)
            ->where('transaction_type_id', TransactionType::CDJ()->id)
            ->where('payment_type_id', PaymentTypes::CDJ->value)
            ->count())->toBe(3)
        ->and(JournalEntryVoucher::query()->where('reference_number', $referenceNumber)->exists())->toBeFalse();
});

it('rejects a negative-net loan without creating accounting records', function () {
    $items = loanVoucherTestItems(
        $this->voucherAccounts[0],
        $this->voucherAccounts[1],
        '110.00',
        '-10.00',
    );
    $loan = createLoanVoucherTestLoan('100.00', '110.00', $items);
    $referenceNumber = 'INVALID LOAN VOUCHER';

    expect(fn () => app(CreateLoanVoucher::class)->handle(
        $loan,
        loanVoucherTestData($referenceNumber, $items),
    ))->toThrow(ValidationException::class, 'Loans with a negative net amount cannot be posted.');

    $loan->refresh();

    expect($loan->posted)->toBeFalse()
        ->and($loan->disbursement_voucher_id)->toBeNull()
        ->and($loan->journal_entry_voucher_id)->toBeNull()
        ->and(DisbursementVoucher::query()->where('reference_number', $referenceNumber)->exists())->toBeFalse()
        ->and(JournalEntryVoucher::query()->where('reference_number', $referenceNumber)->exists())->toBeFalse()
        ->and(Transaction::query()->where('reference_number', $referenceNumber)->exists())->toBeFalse();
});

it('rejects an edited zero-net voucher that needs a nonzero cash entry', function () {
    $items = loanVoucherTestItems(
        $this->voucherAccounts[0],
        $this->voucherAccounts[1],
        '90.00',
        '10.00',
    );
    $loan = createLoanVoucherTestLoan('100.00', '100.00', $items);
    $referenceNumber = 'UNBALANCED ZERO-NET JEV';

    expect(fn () => app(CreateLoanVoucher::class)->handle(
        $loan,
        loanVoucherTestData($referenceNumber, $items),
    ))->toThrow(ValidationException::class, 'Voucher items must balance without a Cash in Bank entry.');

    expect($loan->refresh()->posted)->toBeFalse()
        ->and(JournalEntryVoucher::query()->where('reference_number', $referenceNumber)->exists())->toBeFalse()
        ->and(Transaction::query()->where('reference_number', $referenceNumber)->exists())->toBeFalse();
});

it('generates a journal entry voucher number from the supplied accounting month', function () {
    expect(JournalEntryVoucher::generateCode(CarbonImmutable::parse('2024-03-31')))
        ->toMatch('/^JEV 2024-03-\d+$/');
});

it('includes both loan voucher previews in the loan details action group', function () {
    $actionNames = collect(ViewLoanDetailsActionGroup::getActions()->getActions())
        ->map(fn ($action): string => $action->getName());

    expect($actionNames)
        ->toContain('dv_view')
        ->toContain('jev_view');
});
