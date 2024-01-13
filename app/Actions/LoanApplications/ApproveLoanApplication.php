<?php

namespace App\Actions\LoanApplications;

use App\Models\LoanApplication;
use Lorisleiva\Actions\Concerns\AsAction;

class ApproveLoanApplication
{
    use AsAction;

    public function handle(LoanApplication $loanApplication)
    {
        $loanApplication->update([
            'status' => LoanApplication::STATUS_APPROVED,
        ]);
    }
}
