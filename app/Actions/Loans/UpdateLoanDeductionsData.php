<?php

namespace App\Actions\Loans;

use App\Models\Loan;
use Lorisleiva\Actions\Concerns\AsAction;

class UpdateLoanDeductionsData
{
    use AsAction;

    public function handle(Loan $loan)
    {
        $loan->outstanding_balance = $loan->gross_amount;
        $loan->deductions_amount = collect($loan->deductions)->sum('amount');
        $loan->service_fee = collect($loan->deductions)->firstWhere('code', 'service_fee')['amount'] ?? 0;
        $loan->cbu_amount = collect($loan->deductions)->firstWhere('code', 'cbu_amount')['amount'] ?? 0;
        $loan->imprest_amount = collect($loan->deductions)->firstWhere('code', 'imprest_amount')['amount'] ?? 0;
        $loan->insurance_amount = collect($loan->deductions)->firstWhere('code', 'insurance_amount')['amount'] ?? 0;
        $loan->loan_buyout_interest = collect($loan->deductions)->firstWhere('code', 'loan_buyout_interest')['amount'] ?? 0;
        $loan->loan_buyout_principal = collect($loan->deductions)->firstWhere('code', 'loan_buyout_principal')['amount'] ?? 0;
        $loan->loan_buyout_id = collect($loan->deductions)->firstWhere('code', 'loan_buyout_principal')['loan_id'] ?? null;
        return $loan;
    }
}
