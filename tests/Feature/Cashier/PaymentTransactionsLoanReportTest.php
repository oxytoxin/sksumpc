<?php

use App\Enums\PaymentTypes;
use App\Filament\App\Pages\Cashier\Reports\PaymentTransactions;
use App\Models\Loan;
use App\Models\LoanBilling;
use App\Models\LoanPayment;
use App\Models\User;
use Carbon\CarbonImmutable;
use Illuminate\Foundation\Testing\DatabaseTransactions;

use function Pest\Laravel\actingAs;
use function Pest\Laravel\get;

uses(DatabaseTransactions::class);

beforeEach(function (): void {
    actingAs(User::query()->firstOrFail());
    config()->set('app.transaction_date', CarbonImmutable::parse('2099-01-15'));
});

it('allows access to the payment transactions page', function (): void {
    get(route('filament.app.pages.payment-transactions'))
        ->assertOk();
});

it('groups billing-backed loan payments into one report row', function (): void {
    $transactionDate = CarbonImmutable::parse('2099-01-15');
    $loan = Loan::query()
        ->whereNotNull('loan_account_id')
        ->whereNotNull('member_id')
        ->whereNotNull('loan_type_id')
        ->firstOrFail();

    $billingReference = 'LB-2099-001';

    $billing = LoanBilling::withoutEvents(function () use ($loan, $transactionDate, $billingReference): LoanBilling {
        return LoanBilling::query()->create([
            'date' => $transactionDate,
            'or_date' => $transactionDate,
            'payment_type_id' => PaymentTypes::CASH->value,
            'reference_number' => $billingReference,
            'name' => 'Test Loan Billing',
            'or_number' => 'OR-2099-001',
            'loan_type_id' => $loan->loan_type_id,
            'posted' => true,
            'for_or' => false,
        ]);
    });

    LoanPayment::create([
        'loan_billing_id' => $billing->id,
        'loan_id' => $loan->id,
        'member_id' => $loan->member_id,
        'amount' => '100.00',
        'interest_payment' => '20.00',
        'principal_payment' => '80.00',
        'surcharge_payment' => '0.00',
        'payment_type_id' => PaymentTypes::CASH->value,
        'reference_number' => $billingReference,
        'transaction_date' => $transactionDate,
    ]);

    LoanPayment::create([
        'loan_billing_id' => $billing->id,
        'loan_id' => $loan->id,
        'member_id' => $loan->member_id,
        'amount' => '150.00',
        'interest_payment' => '20.00',
        'principal_payment' => '130.00',
        'surcharge_payment' => '5.00',
        'payment_type_id' => PaymentTypes::CASH->value,
        'reference_number' => $billingReference,
        'transaction_date' => $transactionDate,
    ]);

    LoanPayment::create([
        'loan_id' => $loan->id,
        'member_id' => $loan->member_id,
        'amount' => '75.00',
        'interest_payment' => '15.00',
        'principal_payment' => '60.00',
        'surcharge_payment' => '0.00',
        'payment_type_id' => PaymentTypes::CASH->value,
        'reference_number' => 'SINGLE-2099-001',
        'transaction_date' => $transactionDate,
    ]);

    $records = app(PaymentTransactions::class)
        ->getLoanReportQuery()
        ->whereDate('loan_payments.transaction_date', $transactionDate)
        ->get();

    expect($records)->toHaveCount(2);

    $billingRow = $records->firstWhere('reference_number', $billingReference);
    $standaloneRow = $records->firstWhere('reference_number', 'SINGLE-2099-001');

    expect($billingRow)->not->toBeNull()
        ->and($billingRow->member_name)->toBe($billingReference)
        ->and($billingRow->account_number)->toBe($billingReference)
        ->and($billingRow->loan_type_name)->toBe($loan->loan_type->name)
        ->and($billingRow->transaction_date->format('Y-m-d'))->toBe('2099-01-15')
        ->and($billingRow->amount)->toBe('250.00')
        ->and($billingRow->principal_payment)->toBe('210.00')
        ->and($billingRow->interest_payment)->toBe('40.00')
        ->and($billingRow->surcharge_payment)->toBe('5.00');

    expect($standaloneRow)->not->toBeNull()
        ->and($standaloneRow->member_name)->toBe($loan->member->full_name)
        ->and($standaloneRow->account_number)->toBe($loan->loan_account->number)
        ->and($standaloneRow->loan_type_name)->toBe($loan->loan_type->name)
        ->and($standaloneRow->transaction_date->format('Y-m-d'))->toBe('2099-01-15')
        ->and($standaloneRow->amount)->toBe('75.00')
        ->and($standaloneRow->principal_payment)->toBe('60.00')
        ->and($standaloneRow->interest_payment)->toBe('15.00')
        ->and($standaloneRow->surcharge_payment)->toBe('0.00');
});

it('applies the loan report date range without ambiguous columns', function (): void {
    $transactionDate = CarbonImmutable::parse('2099-01-15');
    $loan = Loan::query()
        ->whereNotNull('loan_account_id')
        ->whereNotNull('member_id')
        ->whereNotNull('loan_type_id')
        ->firstOrFail();

    LoanPayment::create([
        'loan_id' => $loan->id,
        'member_id' => $loan->member_id,
        'amount' => '75.00',
        'interest_payment' => '15.00',
        'principal_payment' => '60.00',
        'surcharge_payment' => '0.00',
        'payment_type_id' => PaymentTypes::CASH->value,
        'reference_number' => 'SINGLE-2099-002',
        'transaction_date' => $transactionDate,
    ]);

    $records = app(PaymentTransactions::class)
        ->applyLoanReportDateRange(
            app(PaymentTransactions::class)->getLoanReportQuery(),
            $transactionDate->startOfDay(),
            $transactionDate->endOfDay(),
        )
        ->get();

    expect($records)->toHaveCount(1)
        ->and($records->first()->reference_number)->toBe('SINGLE-2099-002');
});
