<?php

namespace App\Actions\Loans;

use App\Actions\CapitalSubscription\PayCapitalSubscription;
use App\Actions\Imprests\DepositToImprestAccount;
use App\Models\Loan;
use App\Models\LoanApplication;
use App\Models\TransactionType;
use App\Oxytoxin\DTO\CapitalSubscription\CapitalSubscriptionPaymentData;
use App\Oxytoxin\DTO\Loan\LoanPaymentData;
use App\Oxytoxin\DTO\MSO\ImprestData;
use App\Oxytoxin\Providers\LoansProvider;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Lorisleiva\Actions\Concerns\AsAction;

class RunLoanProcessesAfterPosting
{
    use AsAction;

    public function handle(Loan $loan)
    {
        DB::beginTransaction();
        $loan->loan_application->update([
            'status' => LoanApplication::STATUS_POSTED,
        ]);
        $member = $loan->member;
        $amortization_schedule = LoansProvider::generateAmortizationSchedule($loan);
        $loan->loan_amortizations()->createMany($amortization_schedule);
        DB::commit();
    }
}
