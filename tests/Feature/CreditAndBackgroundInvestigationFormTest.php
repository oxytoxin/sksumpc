<?php

use App\Models\LoanApplication;
use App\Models\LoanApplicationComaker;
use App\Models\Member;
use App\Models\User;
use Illuminate\Foundation\Testing\DatabaseTransactions;

use function Pest\Laravel\actingAs;

uses(DatabaseTransactions::class);

beforeEach(function () {
    actingAs(User::find(9)); // crecom-secretary
});

it('auto-populates all employment and income verification fields from comaker CIBI records on the form page', function () {
    // Create borrower with CIBI record
    $borrower = Member::create([
        'first_name' => 'Juan',
        'last_name' => 'Dela Cruz',
        'membership_date' => today(),
        'patronage_status_id' => 1,
    ]);

    $borrower->credit_and_background()->create([
        'present_employer' => 'Borrower Corp',
        'office_address' => '123 Main St',
        'business_form' => 'Corporation',
        'nature_of_business' => 'Technology',
        'year_connected' => 5,
        'position' => 'Manager',
        'employment_status' => 'Regular',
        'basic_salary' => 50000.00,
        'allowances' => 5000.00,
        'business_income' => 10000.00,
        'other_income' => 2000.00,
        'monthly_income' => 67000.00,
        'annual_income' => 804000.00,
    ]);

    // Create comaker with CIBI record
    $comaker1 = Member::create([
        'first_name' => 'Maria',
        'last_name' => 'Santos',
        'membership_date' => today(),
        'patronage_status_id' => 1,
    ]);

    $comaker1->credit_and_background()->create([
        'present_employer' => 'Comaker Inc',
        'office_address' => '456 Oak Ave',
        'business_form' => 'Sole Proprietorship',
        'nature_of_business' => 'Retail',
        'year_connected' => 3,
        'position' => 'Supervisor',
        'employment_status' => 'Probationary',
        'basic_salary' => 30000.00,
        'allowances' => 3000.00,
        'business_income' => 5000.00,
        'other_income' => 1000.00,
        'monthly_income' => 39000.00,
        'annual_income' => 468000.00,
    ]);

    // Create loan application
    $loanApplication = LoanApplication::create([
        'member_id' => $borrower->id,
        'loan_type_id' => 1,
        'desired_amount' => 50000,
        'number_of_terms' => 12,
        'monthly_payment' => 4444.44,
        'transaction_date' => today(),
        'status' => LoanApplication::STATUS_PROCESSING,
        'priority_number' => 'TEST0001',
    ]);

    // Create comaker record
    LoanApplicationComaker::create([
        'loan_application_id' => $loanApplication->id,
        'member_id' => $comaker1->id,
    ]);

    // Visit the CIBI form page via HTTP
    $this->get("/loan-applications/cibi-form/{$loanApplication->id}")
        ->assertOk()
        ->assertSee('Borrower Corp')
        ->assertSee('Comaker Inc')
        ->assertSee('123 Main St')
        ->assertSee('456 Oak Ave')
        ->assertSee('Corporation')
        ->assertSee('Sole Proprietorship')
        ->assertSee('Technology')
        ->assertSee('Retail')
        ->assertSee('Manager')
        ->assertSee('Supervisor')
        ->assertSee('Regular')
        ->assertSee('Probationary')
        ->assertSee('804000.00')
        ->assertSee('468000.00')
        ->assertSee('50000.00')
        ->assertSee('30000.00')
        ->assertSee('67000.00')
        ->assertSee('39000.00')
        ->assertSee('5000.00')
        ->assertSee('1000.00');
});

it('only populates borrower fields when no comakers exist', function () {
    $borrower = Member::create([
        'first_name' => 'Pedro',
        'last_name' => 'Gonzales',
        'membership_date' => today(),
        'patronage_status_id' => 1,
    ]);

    $borrower->credit_and_background()->create([
        'present_employer' => 'Solo Inc',
        'office_address' => '789 Pine Rd',
        'business_form' => 'Corporation',
        'nature_of_business' => 'Services',
        'year_connected' => 10,
        'position' => 'CEO',
        'employment_status' => 'Regular',
        'basic_salary' => 100000.00,
        'allowances' => 15000.00,
        'business_income' => 30000.00,
        'other_income' => 5000.00,
        'monthly_income' => 150000.00,
        'annual_income' => 1800000.00,
    ]);

    $loanApplication = LoanApplication::create([
        'member_id' => $borrower->id,
        'loan_type_id' => 1,
        'desired_amount' => 25000,
        'number_of_terms' => 6,
        'monthly_payment' => 4444.44,
        'transaction_date' => today(),
        'status' => LoanApplication::STATUS_PROCESSING,
        'priority_number' => 'TEST0002',
    ]);

    $this->get("/loan-applications/cibi-form/{$loanApplication->id}")
        ->assertOk()
        ->assertSee('Solo Inc')
        ->assertSee('789 Pine Rd')
        ->assertSee('CEO')
        ->assertSee('100000.00')
        ->assertSee('15000.00')
        ->assertSee('1800000.00');
});
