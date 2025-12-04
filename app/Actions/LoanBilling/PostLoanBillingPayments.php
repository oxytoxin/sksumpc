<?php

namespace App\Actions\LoanBilling;

use App\Actions\Loans\PayLoan;
use App\Actions\Transactions\CreateTransaction;
use App\Enums\FromBillingTypes;
use App\Enums\PaymentTypes;
use App\Models\Account;
use App\Models\LoanBilling;
use App\Models\LoanBillingPayment;
use App\Models\TransactionType;
use App\Oxytoxin\DTO\Loan\LoanPaymentData;
use App\Oxytoxin\DTO\Transactions\TransactionData;
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
        $transactionType = TransactionType::CRJ();
        $cash_in_bank_account_id = Account::getCashInBankGF()->id;
        $cash_on_hand_account_id = Account::getCashOnHand()->id;
        $loanBilling->loan_billing_payments()->each(function (LoanBillingPayment $lp) use ($loanBilling, $transactionType, $cash_in_bank_account_id, $cash_on_hand_account_id) {

            app(PayLoan::class)->handle($lp->loan, new LoanPaymentData(
                payment_type_id: $loanBilling->payment_type_id,
                reference_number: $loanBilling->or_number,
                amount: $lp->amount_paid,
                remarks: $loanBilling->name,
                transaction_date: $loanBilling->or_date ?? $loanBilling->date,
                from_billing_type: FromBillingTypes::LOAN_BILLING->value
            ), $transactionType);

            $lp->update([
                'posted' => true,
            ]);

            $data = new TransactionData(
                account_id: $cash_in_bank_account_id,
                transactionType: $transactionType,
                reference_number: $loanBilling->or_number,
                payment_type_id: $loanBilling->payment_type_id,
                debit: $lp->amount_paid,
                member_id: $lp->member_id,
                transaction_date: $loanBilling->or_date ?? $loanBilling->date,
                payee: $lp->member->full_name,
                from_billing_type: FromBillingTypes::LOAN_BILLING->value
            );

            if ($data->payment_type_id == PaymentTypes::ADA->value) {
                $data->account_id = $cash_in_bank_account_id;
            } else {
                $data->account_id = $cash_on_hand_account_id;
            }
            app(CreateTransaction::class)->handle($data);
        });

        $loanBilling->update([
            'posted' => true,
        ]);
        DB::commit();
    }
}
