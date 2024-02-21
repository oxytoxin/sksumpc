<?php

namespace App\Actions\LoanBilling;

use App\Models\Account;
use App\Models\LoanBilling;
use App\Actions\Loans\PayLoan;
use App\Models\TransactionType;
use App\Models\LoanBillingPayment;
use Illuminate\Support\Facades\DB;
use Filament\Notifications\Notification;
use Lorisleiva\Actions\Concerns\AsAction;
use App\Oxytoxin\DTO\Loan\LoanPaymentData;
use App\Actions\Transactions\CreateTransaction;
use App\Oxytoxin\DTO\Transactions\TransactionData;

class PostLoanBillingPayments
{
    use AsAction;

    public function handle(LoanBilling $loanBilling)
    {
        if (!$loanBilling->reference_number || !$loanBilling->payment_type_id) {
            return Notification::make()->title('Billing reference number and payment type is missing!')->danger()->send();
        }
        DB::beginTransaction();
        $loanBilling->loan_billing_payments()->each(function (LoanBillingPayment $lp) use ($loanBilling) {
            app(PayLoan::class)->handle($lp->loan, new LoanPaymentData(
                payment_type_id: $loanBilling->payment_type_id,
                reference_number: $loanBilling->or_number,
                amount: $lp->amount_paid,
                remarks: $loanBilling->name,
            ), TransactionType::firstWhere('name', 'CRJ'));
            $lp->update([
                'posted' => true,
            ]);
        });
        app(CreateTransaction::class)->handle(new TransactionData(
            account_id: Account::getCashInBankGF()->id,
            transactionType: TransactionType::firstWhere('name', 'CRJ'),
            reference_number: $loanBilling->reference_number,
            debit: $loanBilling->loan_billing_payments()->sum('amount_paid'),
            remarks: 'Loan Billing Payment'
        ));
        $loanBilling->update([
            'posted' => true,
        ]);
        DB::commit();
    }
}
