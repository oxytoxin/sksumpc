<?php

namespace App\Actions\LoanApplications;

use App\Models\LoanApplication;

class ApproveLoanApplication
{
    public function handle(LoanApplication $loanApplication, $approval_date = null)
    {
        $loanApplication->update([
            'status' => LoanApplication::STATUS_APPROVED,
            'approval_date' => $approval_date ?? today(),
        ]);
    }
}
