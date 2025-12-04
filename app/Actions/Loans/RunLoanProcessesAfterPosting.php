<?php

namespace App\Actions\Loans;

use App\Models\Loan;
use App\Models\LoanApplication;
use Illuminate\Support\Facades\DB;

class RunLoanProcessesAfterPosting
{
    public function handle(Loan $loan)
    {
        DB::beginTransaction();
        $loan->loan_application->update([
            'status' => LoanApplication::STATUS_POSTED,
        ]);
        DB::commit();
    }
}
