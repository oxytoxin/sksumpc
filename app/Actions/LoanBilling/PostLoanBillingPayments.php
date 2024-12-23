<?php

namespace App\Actions\LoanBilling;

use App\Actions\Loans\PayLoan;
use App\Models\LoanBilling;
use App\Models\LoanBillingPayment;
use App\Models\TransactionType;
use App\Oxytoxin\DTO\Loan\LoanPaymentData;
use Filament\Notifications\Notification;
use Illuminate\Support\Facades\DB;


class PostLoanBillingPayments
{


    public function handle(LoanBilling $loanBilling)
    {
        if (! $loanBilling->reference_number || ! $loanBilling->payment_type_id) {
            return Notification::make()->title('Billing reference number and payment type is missing!')->danger()->send();
        }
        DB::beginTransaction();
        $loanBilling->loan_billing_payments()->each(function (LoanBillingPayment $lp) use ($loanBilling) {
            app(PayLoan::class)->handle($lp->loan, new LoanPaymentData(
                payment_type_id: $loanBilling->payment_type_id,
                reference_number: $loanBilling->or_number,
                amount: $lp->amount_paid,
                remarks: $loanBilling->name,
                transaction_date: $loanBilling->date,
            ), TransactionType::CRJ());
            $lp->update([
                'posted' => true,
            ]);
        });
        $loanBilling->update([
            'posted' => true,
        ]);
        DB::commit();
    }
}
