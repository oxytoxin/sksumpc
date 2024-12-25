<?php

namespace App\Actions\Loans;

use App\Models\Loan;
use App\Models\LoanApplication;
use App\Oxytoxin\Providers\LoansProvider;
use Illuminate\Support\Facades\DB;

class RunLoanProcessesAfterPosting
{
    public function handle(Loan $loan)
    {
        DB::beginTransaction();
        $loan->loan_application->update([
            'status' => LoanApplication::STATUS_POSTED,
        ]);
        $amortization_schedule = LoansProvider::generateAmortizationSchedule($loan);
        $loan->loan_amortizations()->createMany($amortization_schedule);
        DB::commit();
    }
}
