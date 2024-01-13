<?php

namespace App\Actions\LoanApplications;

use App\Models\LoanApplication;
use App\Oxytoxin\DTO\Loan\LoanApplicationData;
use Lorisleiva\Actions\Concerns\AsAction;

class CreateNewLoanApplication
{
    use AsAction;

    public function handle(LoanApplicationData $loanApplicationData)
    {
        return LoanApplication::create([
            'member_id' => $loanApplicationData->member_id,
            'loan_type_id' => $loanApplicationData->loan_type_id,
            'number_of_terms' => $loanApplicationData->number_of_terms,
            'priority_number' => $loanApplicationData->priority_number,
            'desired_amount' => $loanApplicationData->desired_amount,
            'monthly_payment' => $loanApplicationData->monthly_payment,
            'purpose' => $loanApplicationData->purpose,
        ]);
    }
}
