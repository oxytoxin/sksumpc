<?php

use App\Actions\Memberships\BuildAccountClosureDisclosure;
use App\Actions\Memberships\CloseMemberAccount;
use App\Actions\MSO\DepositToMsoAccount;
use App\Actions\MSO\WithdrawFromMsoAccount;
use App\Enums\MsoType;
use App\Enums\PaymentTypes;
use App\Filament\App\Pages\Cashier\PaymentTransactions;
use App\Filament\App\Resources\DisbursementVoucherResource\Pages\ManageDisbursementVouchers;
use App\Filament\App\Resources\JournalEntryVoucherResource\Pages\ManageJournalEntryVouchers;
use App\Filament\App\Resources\MemberResource\Pages\ListMembers;
use App\Models\DisbursementVoucher;
use App\Models\JournalEntryVoucher;
use App\Models\Loan;
use App\Models\Member;
use App\Models\MembershipStatus;
use App\Models\SavingsAccount;
use App\Models\TransactionType;
use App\Models\User;
use App\Oxytoxin\DTO\Transactions\TransactionData;
use App\Oxytoxin\Providers\LoansProvider;
use Carbon\CarbonImmutable;
use Filament\Actions\CreateAction;
use Filament\Actions\Testing\TestAction;
use Filament\Facades\Filament;
use Filament\Forms\Components\Select;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Validation\ValidationException;

use function Pest\Laravel\actingAs;
use function Pest\Livewire\livewire;

uses(DatabaseTransactions::class);

beforeEach(function () {
    actingAs(User::query()->firstOrFail());
    Filament::setCurrentPanel(Filament::getPanel('app'));
    config()->set('app.transaction_date', CarbonImmutable::parse('2026-09-22'));
});

function savingsAccountWithBalance(): SavingsAccount
{
    $account = SavingsAccount::query()
        ->active()
        ->whereHas('member', fn ($query) => $query->whereNull('terminated_at'))
        ->get()
        ->first(fn (SavingsAccount $account): bool => round((float) $account->savings()->sum('amount'), 2) > 0);

    expect($account)->not->toBeNull();

    return $account;
}

it('builds a balanced account closure disclosure with savings details', function () {
    $account = savingsAccountWithBalance();
    $disclosure = app(BuildAccountClosureDisclosure::class)->handle($account->member);
    $debits = collect($disclosure['voucher_items'])->sum(fn (array $item): float => (float) ($item['debit'] ?? 0));
    $credits = collect($disclosure['voucher_items'])->sum(fn (array $item): float => (float) ($item['credit'] ?? 0));

    expect($disclosure['savings'])->toBeGreaterThan(0)
        ->and($disclosure['total_savings'])->toBe(round($disclosure['savings'] + $disclosure['imprest'], 2))
        ->and($disclosure['savings_accounts'])->not->toBeEmpty()
        ->and(round($debits, 2))->toBe(round($credits, 2))
        ->and(collect($disclosure['voucher_items'])->every(
            fn (array $item): bool => data_get($item, 'details.account_closure') === true
        ))->toBeTrue();
});

it('credits every payable loan for its full settlement amount', function () {
    $loan = Loan::query()
        ->payable()
        ->whereHas('member', fn ($query) => $query->whereNull('terminated_at'))
        ->with(['loan_account', 'member'])
        ->firstOrFail();
    $disclosure = app(BuildAccountClosureDisclosure::class)->handle($loan->member);
    $loanItem = collect($disclosure['voucher_items'])->firstWhere('account_id', $loan->loan_account_id);

    expect($loanItem)->not->toBeNull()
        ->and((float) $loanItem['credit'])->toBe(LoansProvider::computeSettlementAmount($loan, config('app.transaction_date')));
});

it('loads the same closure disclosure into DV and JEV forms', function (string $pageClass, string $itemsKey) {
    $account = savingsAccountWithBalance();
    $disclosure = app(BuildAccountClosureDisclosure::class)->handle($account->member);

    livewire($pageClass)
        ->mountAction(CreateAction::class)
        ->fillForm([
            'action' => 'closed_account',
            'closure_member_id' => $account->member_id,
        ])
        ->assertActionDataSet(fn (array $state): array => [
            'name' => $account->member->full_name,
            'description' => 'CLOSED ACCOUNT - WDL OF MEMBERSHIP',
            $itemsKey => $disclosure['voucher_items'],
        ]);
})->with([
    'disbursement voucher' => [ManageDisbursementVouchers::class, 'disbursement_voucher_items'],
    'journal entry voucher' => [ManageJournalEntryVouchers::class, 'journal_entry_voucher_items'],
]);

it('posts full-balance closure rows through DV and JEV', function (
    string $pageClass,
    string $voucherModel,
    string $itemsKey,
    string $referenceNumber,
) {
    $account = savingsAccountWithBalance();
    $disclosure = app(BuildAccountClosureDisclosure::class)->handle($account->member);
    app(CloseMemberAccount::class)->handle(
        member: $account->member,
        bodResolution: 'TEST BOD RESOLUTION',
        terminationDate: config('app.transaction_date'),
        voucherNumber: $referenceNumber,
        capitalAmount: $disclosure['cbu'],
    );

    livewire($pageClass)
        ->callAction(CreateAction::class, data: [
            'action' => 'closed_account',
            'closure_member_id' => $account->member_id,
            'name' => $account->member->full_name,
            'address' => $account->member->address ?? 'Test address',
            'reference_number' => $referenceNumber,
            'voucher_number' => $referenceNumber,
            'description' => 'CLOSED ACCOUNT - WDL OF MEMBERSHIP',
            'compute_net' => false,
            $itemsKey => $disclosure['voucher_items'],
        ])
        ->assertHasNoFormErrors();

    expect($voucherModel::query()->where('reference_number', $referenceNumber)->exists())->toBeTrue()
        ->and(round((float) $account->savings()->sum('amount'), 2))->toBe(0.0);
})->with([
    'disbursement voucher' => [
        ManageDisbursementVouchers::class,
        DisbursementVoucher::class,
        'disbursement_voucher_items',
        'TEST-CLOSURE-DV-001',
    ],
    'journal entry voucher' => [
        ManageJournalEntryVouchers::class,
        JournalEntryVoucher::class,
        'journal_entry_voucher_items',
        'TEST-CLOSURE-JEV-001',
    ],
]);

it('shows the closure disclosure from the membership action', function () {
    $account = savingsAccountWithBalance();

    livewire(ListMembers::class)
        ->mountAction(TestAction::make('closed_account')->table($account->member))
        ->assertMountedActionModalSee('Total Savings')
        ->assertMountedActionModalSee('Loan Full Payment');
});

it('closes the membership and all member accounts in one transaction', function () {
    $member = Member::query()
        ->whereNull('terminated_at')
        ->whereHas('accounts', fn ($query) => $query->active())
        ->firstOrFail();

    $status = app(CloseMemberAccount::class)->handle(
        member: $member,
        bodResolution: 'TEST BOD RESOLUTION',
        terminationDate: config('app.transaction_date'),
        voucherNumber: 'TEST-CLOSE-ACCOUNT-001',
        capitalAmount: 1250,
    );

    expect($status->type)->toBe(MembershipStatus::TERMINATION)
        ->and($status->termination_voucher_number)->toBe('TEST-CLOSE-ACCOUNT-001')
        ->and($member->fresh()->terminated_at->isSameDay(config('app.transaction_date')))->toBeTrue()
        ->and($member->accounts()->active()->exists())->toBeFalse()
        ->and($member->accounts()->closed()->count())->toBeGreaterThan(0);
});

it('removes closed members and accounts from cashier search', function () {
    $account = savingsAccountWithBalance();
    $member = $account->member;

    app(CloseMemberAccount::class)->handle(
        member: $member,
        bodResolution: 'TEST BOD RESOLUTION',
        terminationDate: config('app.transaction_date'),
        voucherNumber: 'TEST-CASHIER-SEARCH-CLOSURE',
        capitalAmount: 0,
    );

    livewire(PaymentTransactions::class)
        ->assertSchemaComponentExists(
            'member_id',
            'form',
            fn (Select $component): bool => ! array_key_exists($member->id, $component->getOptions()),
        )
        ->assertSchemaComponentExists(
            'lookup_account_number',
            'form',
            fn (Select $component): bool => ! array_key_exists($account->number, $component->getOptions()),
        );
});

it('rejects deposits and withdrawals on a closed savings account', function () {
    $account = savingsAccountWithBalance();
    $account->close('Test closure');

    $deposit = new TransactionData(
        account_id: $account->id,
        transactionType: TransactionType::CRJ(),
        reference_number: 'TEST-CLOSED-DEPOSIT',
        payment_type_id: PaymentTypes::CASH->value,
        credit: 100,
        member_id: $account->member_id,
    );
    $withdrawal = new TransactionData(
        account_id: $account->id,
        transactionType: TransactionType::CRJ(),
        reference_number: 'TEST-CLOSED-WITHDRAWAL',
        payment_type_id: PaymentTypes::CASH->value,
        debit: 100,
        member_id: $account->member_id,
    );

    expect(fn () => app(DepositToMsoAccount::class)->handle(MsoType::SAVINGS, $deposit))
        ->toThrow(ValidationException::class, 'Closed accounts cannot accept deposits.')
        ->and(fn () => app(WithdrawFromMsoAccount::class)->handle(MsoType::SAVINGS, $withdrawal))
        ->toThrow(ValidationException::class, 'Closed accounts cannot accept withdrawals.');
});

it('allows a voucher closure entry to withdraw the full savings balance', function () {
    $account = savingsAccountWithBalance();
    $balance = round((float) $account->savings()->sum('amount'), 2);
    $account->close('Membership closure');
    $account->member->update(['terminated_at' => config('app.transaction_date')]);
    $transaction = new TransactionData(
        account_id: $account->id,
        transactionType: TransactionType::JEV(),
        reference_number: 'TEST-FULL-BALANCE-CLOSURE',
        payment_type_id: PaymentTypes::JEV->value,
        debit: $balance,
        member_id: $account->member_id,
        allow_full_balance_withdrawal: true,
    );

    app(WithdrawFromMsoAccount::class)->handle(MsoType::SAVINGS, $transaction);

    expect(round((float) $account->savings()->sum('amount'), 2))->toBe(0.0);
});
