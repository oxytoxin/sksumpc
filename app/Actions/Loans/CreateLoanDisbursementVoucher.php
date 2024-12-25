<?php

namespace App\Actions\Loans;

use App\Models\Loan;

class CreateLoanDisbursementVoucher
{
    public function handle(Loan $loan)
    {
        $loan->update([
            'posted' => true,
        ]);
    }
}
