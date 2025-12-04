<?php

namespace App\Actions\Loans;

use App\Models\Loan;

class UpdateLoanDeductionsData
{
    public function handle(Loan $loan)
    {
        $loan->outstanding_balance = $loan->gross_amount;
        $loan->deductions_amount = collect($loan->disclosure_sheet_items)->sum('credit') - collect($loan->disclosure_sheet_items)->firstWhere('code', 'net_amount')['credit'];
        $loan->service_fee = collect($loan->disclosure_sheet_items)->firstWhere('code', 'service_fee')['credit'] ?? 0;
        $loan->cbu_amount = collect($loan->disclosure_sheet_items)->firstWhere('code', 'cbu_amount')['credit'] ?? 0;
        $loan->imprest_amount = collect($loan->disclosure_sheet_items)->firstWhere('code', 'imprest_amount')['credit'] ?? 0;
        $loan->insurance_amount = collect($loan->disclosure_sheet_items)->firstWhere('code', 'insurance_amount')['credit'] ?? 0;
        $loan->loan_buyout = collect($loan->disclosure_sheet_items)->firstWhere('code', 'loan_buyout')['credit'] ?? 0;
        $loan->loan_buyout_id = collect($loan->disclosure_sheet_items)->firstWhere('code', 'loan_buyout')['loan_id'] ?? null;

        return $loan;
    }
}
