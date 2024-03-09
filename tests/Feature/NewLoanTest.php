<?php

use App\Models\Loan;
use App\Models\User;
use App\Models\Member;
use App\Models\Account;
use App\Models\LoanApplication;
use App\Livewire\App\LoansTable;
use App\Oxytoxin\DTO\Loan\LoanData;

use function Pest\Laravel\actingAs;
use App\Actions\Loans\CreateNewLoan;
use function Pest\Livewire\livewire;
use App\Oxytoxin\Providers\LoansProvider;
use Filament\Tables\Actions\CreateAction;
use function Pest\Laravel\assertModelExists;
use App\Filament\App\Resources\LoanApplicationResource\Pages\ManageLoanApplications;

it('test loan functions from phpspreadsheet', function () {
});

it('can create a new loan', function () {
    $member = Member::find(664);
    actingAs(User::find(1));
    $la = LoanApplication::create([
        'member_id' => $member->id,
        'loan_type_id' => 2,
        'number_of_terms' => 12,
        'priority_number' => '0000001',
        'desired_amount' => LoanApplication::count() * 10000 + 15000,
        'processor_id' => 1,
        'reference_number' => 'ACL-2023-000001',
        'monthly_payment' => 888.4900,
        'transaction_date' => today(),
        'status' => LoanApplication::STATUS_APPROVED,
        'approvals' => [
            ['name' => 'CATHERINE A. LEGASPI', 'position' => 'Crecom-Secretary'],
            ['name' => 'JACQUILINE B. CANDIDO', 'position' => 'Crecom-Chairperson'],
            ['name' => 'JUVEN LACONSE', 'position' => 'Crecom-Vice Chairperson'],
            ['name' => 'FLORA C. DAMANDAMAN', 'position' => 'Manager'],
        ],
    ]);
    assertModelExists($la);
    $disclosure_sheet_items = LoansProvider::getDisclosureSheetItems($la->loan_type, $la->desired_amount, $la->member);
    $accounts = Account::withCode()->find(collect($disclosure_sheet_items)->pluck('account_id'));
    $items = collect($disclosure_sheet_items)->map(function ($item) use ($accounts) {
        $item['name'] = $accounts->find($item['account_id'])->code;
        return $item;
    })->toArray();
    $loanData = new LoanData(
        member_id: $la->member_id,
        loan_application_id: $la->id,
        loan_type_id: $la->loan_type->id,
        reference_number: $la->reference_number,
        interest_rate: $la->loan_type->interest_rate,
        disclosure_sheet_items: $items,
        priority_number: $la->priority_number,
        gross_amount: $la->desired_amount,
        number_of_terms: $la->number_of_terms,
        interest: LoansProvider::computeInterest(
            amount: $la->desired_amount,
            loanType: $la->loan_type,
            number_of_terms: $la->number_of_terms,
        ),
        monthly_payment: LoansProvider::computeMonthlyPayment(
            amount: $la->desired_amount,
            loanType: $la->loan_type,
            number_of_terms: $la->number_of_terms,
        ),
        release_date: today(),
        transaction_date: today(),
    );
    app(CreateNewLoan::class)->handle($la, $loanData);
    // $loan = Loan::latest()->first();
    // $loan->posted = true;
    // $loan->save();
});
