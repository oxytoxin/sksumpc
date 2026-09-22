<?php

use App\Actions\Loans\PayLoan;
use App\Enums\LoanTypes;
use App\Enums\PaymentTypes;
use App\Models\Account;
use App\Models\Loan;
use App\Models\LoanApplication;
use App\Models\LoanPayment;
use App\Models\LoanType;
use App\Models\Transaction;
use App\Models\TransactionType;
use App\Models\User;
use App\Oxytoxin\DTO\Loan\LoanPaymentData;
use App\Oxytoxin\Providers\LoansProvider;
use Carbon\CarbonImmutable;
use Illuminate\Foundation\Testing\DatabaseTransactions;

use function Pest\Laravel\actingAs;

uses(DatabaseTransactions::class);

function makeSpecialLoanForSurcharge(string $outstandingBalance = '10000.00'): Loan
{
    $loanType = (new LoanType)->forceFill([
        'surcharge_rate' => '0.0100',
    ]);
    $loanType->id = LoanTypes::SPECIAL_LOAN->value;

    $loan = (new Loan)->forceFill([
        'loan_type_id' => LoanTypes::SPECIAL_LOAN->value,
        'transaction_date' => CarbonImmutable::parse('2026-01-01'),
        'release_date' => CarbonImmutable::parse('2026-01-01'),
        'maturity_date' => CarbonImmutable::parse('2026-09-18'),
        'outstanding_balance' => $outstandingBalance,
    ]);
    $loan->setRelation('loan_type', $loanType);

    return $loan;
}

it('counts Saturday and excludes Sunday from the seven-working-day surcharge grace period', function (): void {
    $loan = makeSpecialLoanForSurcharge();

    expect(LoansProvider::getSurchargeStartDate($loan)->format('Y-m-d'))->toBe('2026-09-28')
        ->and(LoansProvider::computeSurchargeDue($loan, '2026-09-26'))->toBe(0.0)
        ->and(LoansProvider::computeSurchargeDue($loan, '2026-09-27'))->toBe(0.0)
        ->and(LoansProvider::computeSurchargeDue($loan, '2026-09-28'))->toBe(100.0);
});

it('calculates the special-loan maturity date for historical backfills', function (): void {
    $loan = makeSpecialLoanForSurcharge();
    $loan->release_date = CarbonImmutable::parse('2026-06-05');
    $loan->maturity_date = null;

    expect(LoansProvider::calculateMaturityDate($loan)->format('Y-m-d'))->toBe('2026-11-20');
});

it('allocates a late special-loan payment to surcharge then interest then principal', function (): void {
    actingAs(User::query()->firstOrFail());

    $loan = Loan::query()
        ->whereHas('loan_type', fn ($query) => $query->where('code', 'SL'))
        ->whereNotNull('loan_account_id')
        ->whereNotNull('loan_application_id')
        ->firstOrFail();

    Loan::withoutEvents(fn () => $loan->update([
        'transaction_date' => '2099-05-01',
        'release_date' => '2099-05-01',
        'maturity_date' => '2099-05-20',
        'outstanding_balance' => '50000.00',
        'posted' => true,
    ]));
    LoanApplication::withoutEvents(fn () => $loan->loan_application->update([
        'surcharge_start_date' => null,
    ]));
    $loan->refresh()->load(['loan_type', 'loan_account']);
    $loan->payments()->update([
        'unpaid_interest' => 0,
        'surcharge_payment' => 0,
    ]);

    LoanPayment::query()->create([
        'loan_id' => $loan->id,
        'member_id' => $loan->member_id,
        'amount' => '0.00',
        'interest_payment' => '0.00',
        'principal_payment' => '0.00',
        'unpaid_interest' => '100.00',
        'surcharge_payment' => '0.00',
        'payment_type_id' => PaymentTypes::CASH->value,
        'reference_number' => 'SURCHARGE-PRIOR-INTEREST',
        'transaction_date' => '2099-05-21',
    ]);

    $transactionDate = LoansProvider::getSurchargeStartDate($loan);
    $payment = app(PayLoan::class)->handle(
        $loan,
        new LoanPaymentData(
            payment_type_id: PaymentTypes::CASH->value,
            reference_number: 'SURCHARGE-ALLOCATION-TEST',
            amount: '650.00',
            transaction_date: $transactionDate,
        ),
        TransactionType::CRJ(),
    );

    expect($payment->surcharge_payment)->toBe('500.00')
        ->and($payment->interest_payment)->toBe('100.00')
        ->and($payment->principal_payment)->toBe('50.00')
        ->and($loan->fresh()->outstanding_balance)->toBe('49950.00')
        ->and(LoansProvider::computeSurchargeDue($loan->fresh()->load('loan_type'), $transactionDate))->toBe(0.0)
        ->and(Transaction::query()
            ->where('reference_number', 'SURCHARGE-ALLOCATION-TEST')
            ->where('account_id', Account::getFinesPenaltiesSurcharges()->id)
            ->where('remarks', 'Member Loan Payment Surcharge')
            ->value('credit'))->toBe('500.00');
});
