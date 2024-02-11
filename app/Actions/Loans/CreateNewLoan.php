<?php

namespace App\Actions\Loans;

use App\Models\Account;
use App\Models\Loan;
use App\Models\LoanApplication;
use App\Models\LoanType;
use Illuminate\Support\Facades\DB;
use App\Oxytoxin\DTO\Loan\LoanData;
use App\Oxytoxin\Providers\LoansProvider;
use Lorisleiva\Actions\Concerns\AsAction;

class CreateNewLoan
{
    use AsAction;

    public function handle(LoanApplication $loanApplication, LoanData $loanData)
    {
        DB::beginTransaction();
        $loanApplication->update([
            'priority_number' => $loanData->priority_number,
        ]);
        $loanType = LoanType::find($loanData->loan_type_id);
        $loans_payable_account = Account::whereNull('member_id')->whereTag('loans_payable')->first();
        $account = Account::create([
            'name' => strtoupper($loanType->name),
            'number' => $loanData->account_number,
            'account_type_id' => $loans_payable_account->account_type_id,
            'member_id' => $loanData->member_id,
            'tag' => 'member_loan',
        ], $loans_payable_account);
        $loan = Loan::create([
            'loan_account_id' => $account->id,
            'priority_number' => $loanData->priority_number,
            'transaction_date' => $loanData->transaction_date,
            'release_date' => $loanData->release_date,
            'gross_amount' => $loanData->gross_amount,
            'number_of_terms' => $loanData->number_of_terms,
            'deductions' => $loanData->deductions,
            'loan_application_id' => $loanData->loan_application_id,
            'reference_number' => $loanData->reference_number,
            'loan_type_id' => $loanData->loan_type_id,
            'interest_rate' => $loanData->interest_rate,
            'interest' => $loanData->interest,
            'member_id' => $loanData->member_id,
            'monthly_payment' => $loanData->monthly_payment,
        ]);
        DB::commit();
        return $loan;
    }
}
