<?php

namespace App\Actions\Loans;

use App\Models\Loan;
use App\Oxytoxin\DTO\Loan\LoanPaymentData;
use App\Oxytoxin\LoansProvider;
use Carbon\Carbon;
use Lorisleiva\Actions\Concerns\AsAction;

class PayLoan
{
    use AsAction;

    public function handle(Loan $loan, LoanPaymentData $loanPaymentData)
    {
        $start = $loan->last_payment?->transaction_date ?? $loan->transaction_date;
        $end = $loanPaymentData->transaction_date;
        $total_days = LoansProvider::getAccruableDays($start, $end);
        $interest_due = LoansProvider::computeAccruedInterest($loan, $loan->outstanding_balance, $total_days);
        $interest_payment = min($loanPaymentData->amount, $interest_due);
        $principal_payment = $loanPaymentData->amount - $interest_payment;
        $loan->payments()->create([
            'buy_out' => $loanPaymentData->buy_out,
            'payment_type_id' => $loanPaymentData->payment_type_id,
            'amount' => $loanPaymentData->amount,
            'interest_payment' => $interest_payment,
            'principal_payment' => $principal_payment,
            'reference_number' => $loanPaymentData->reference_number,
            'remarks' => $loanPaymentData->remarks,
            'transaction_date' => $loanPaymentData->transaction_date
        ]);
    }
}
