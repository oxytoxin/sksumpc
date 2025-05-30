<?php

namespace App\Actions\Loans;

use App\Models\Account;
use App\Models\Loan;
use App\Models\LoanApplication;
use App\Models\LoanType;
use App\Oxytoxin\DTO\Loan\LoanData;
use Illuminate\Support\Facades\DB;

class CreateNewLoan
{
    public function handle(LoanApplication $loanApplication, LoanData $loanData)
    {
        DB::beginTransaction();
        $loanApplication->update([
            'priority_number' => $loanData->priority_number,
        ]);
        $loanType = LoanType::find($loanData->loan_type_id);
        $loans_payable_account = Account::whereNull('member_id')->whereTag('loans_payable')->first();
        $loans_receivable_account = Account::whereNull('member_id')->whereTag('loan_receivables')->whereAccountableType(LoanType::class)->whereAccountableId($loanType->id)->first();
        $account = Account::create([
            'name' => strtoupper($loanType->name),
            'number' => str($loans_payable_account->number)->append('-')->append(str_pad($loanType->id, 4, '0', STR_PAD_LEFT))
                ->append('-')
                ->append($loanData->account_number),
            'account_type_id' => $loans_payable_account->account_type_id,
            'member_id' => $loanData->member_id,
            'tag' => 'member_loans_payable',
        ], $loans_payable_account);
        $account = Account::create([
            'name' => strtoupper($loanType->name),
            'number' => str($loans_receivable_account->number)->append('-')->append($loanData->account_number),
            'account_type_id' => $loans_receivable_account->account_type_id,
            'member_id' => $loanData->member_id,
            'tag' => 'member_loans_receivable',
        ], $loans_receivable_account);
        $loan = Loan::create([
            'loan_account_id' => $account->id,
            'priority_number' => $loanData->priority_number,
            'transaction_date' => $loanData->transaction_date,
            'release_date' => $loanData->release_date,
            'gross_amount' => $loanData->gross_amount,
            'number_of_terms' => $loanData->number_of_terms,
            'disclosure_sheet_items' => $loanData->disclosure_sheet_items,
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
