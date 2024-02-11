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
        $cbu_amount = collect($loan->deductions)->firstWhere('code', 'cbu_amount')['amount'];
        $cbu = $member->capital_subscriptions()->create([
            'number_of_terms' => 0,
            'number_of_shares' => $cbu_amount / $member->member_type->par_value,
            'amount_subscribed' => $cbu_amount,
            'par_value' => $member->member_type->par_value,
            'is_common' => false,
            'code' => Str::random(12),
            'transaction_date' => today(),
        ]);
        app(PayCapitalSubscription::class)->handle($cbu, new CapitalSubscriptionPaymentData(
            payment_type_id: 2,
            reference_number: $loan->reference_number,
            amount: $cbu_amount
        ), TransactionType::firstWhere('name', 'CDJ'));
        app(DepositToImprestAccount::class)->handle($member, new ImprestData(
            payment_type_id: 1,
            reference_number: $loan->reference_number,
            amount: $loan->imprest_amount,
        ), TransactionType::firstWhere('name', 'CDJ'));
        if ($loan->loan_buyout_id) {
            $existing = $member->loans()->find($loan->loan_buyout_id);
            app(PayLoan::class)->handle(loan: $existing, loanPaymentData: new LoanPaymentData(
                buy_out: true,
                payment_type_id: 2,
                reference_number: $loan->reference_number,
                amount: $loan->loan_buyout_principal + $loan->loan_buyout_interest,
            ), transactionType: TransactionType::firstWhere('name', 'CDJ'));
        }
        DB::commit();
    }
}
