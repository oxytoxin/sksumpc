<?php

namespace App\Actions\LoanApplications;

use App\Models\LoanApplication;
use Lorisleiva\Actions\Concerns\AsAction;

class ApproveLoanApplication
{
    use AsAction;

    public function handle(LoanApplication $loanApplication, $approval_date = null)
    {
        $loanApplication->update([
            'status' => LoanApplication::STATUS_APPROVED,
            'approval_date' => $approval_date ?? today()
        ]);
    }
}
