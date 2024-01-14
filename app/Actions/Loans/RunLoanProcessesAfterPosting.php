<?php

namespace App\Actions\Loans;

use App\Actions\Imprests\DepositToImprestAccount;
use App\Models\Loan;
use App\Models\LoanApplication;
use App\Oxytoxin\DTO\MSO\ImprestData;
use App\Oxytoxin\LoansProvider;
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
        $amortization_schedule = LoansProvider::generateAmortizationSchedule($loan);
        $loan->loan_amortizations()->createMany($amortization_schedule);
        $cbu_amount = collect($loan->deductions)->firstWhere('code', 'cbu_amount')['amount'];
        $cbu = $loan->member->capital_subscriptions()->create([
            'number_of_terms' => 0,
            'number_of_shares' => $cbu_amount / $loan->member->member_type->par_value,
            'amount_subscribed' => $cbu_amount,
            'par_value' => $loan->member->member_type->par_value,
            'is_common' => false,
            'code' => Str::random(12),
            'transaction_date' => today(),
        ]);
        $cbu->payments()->create([
            'payment_type_id' => 2,
            'reference_number' => $loan->reference_number,
            'amount' => $cbu_amount,
            'transaction_date' => today(),
        ]);
        app(DepositToImprestAccount::class)->handle($loan->member, new ImprestData(
            payment_type_id: 1,
            reference_number: $loan->reference_number,
            amount: collect($loan->deductions)->firstWhere('code', 'imprest_amount')['amount'],
        ));
        $buyOutPrincipal = collect($loan->deductions)->firstWhere('code', 'buy_out_principal');
        $buyOutInterest = collect($loan->deductions)->firstWhere('code', 'buy_out_interest');
        if ($buyOutPrincipal) {
            $existing = $loan->member->loans()->find($buyOutPrincipal['loan_id']);
            $existing?->payments()->create([
                'payment_type_id' => 2,
                'reference_number' => $loan->reference_number,
                'amount' => ($buyOutPrincipal['amount'] ?? 0) + ($buyOutInterest['amount'] ?? 0),
                'transaction_date' => today(),
            ]);
        }
        DB::commit();
    }
}
