<?php

namespace App\Actions\Loans;

use App\Actions\Transactions\CreateTransaction;
use App\Models\Account;
use App\Models\Loan;
use App\Models\LoanPayment;
use App\Models\LoanType;
use App\Models\TransactionType;
use App\Oxytoxin\DTO\Loan\LoanPaymentData;
use App\Oxytoxin\DTO\Transactions\TransactionData;
use App\Oxytoxin\Providers\LoansProvider;

class PayLoan
{
    public function handle(Loan $loan, LoanPaymentData $loanPaymentData, TransactionType $transactionType): LoanPayment
    {
        $paymentAmount = (float) $loanPaymentData->amount;
        $surchargeDue = LoansProvider::computeSurchargeDue($loan, $loanPaymentData->transaction_date);
        $surchargePayment = max(min($paymentAmount, $surchargeDue), 0);
        $remainingPayment = round($paymentAmount - $surchargePayment, 2);
        $interestDue = LoansProvider::computeInterestDue($loan, $loanPaymentData->transaction_date);
        $interestPayment = max(min($remainingPayment, $interestDue), 0);

        if ($interestPayment < $interestDue) {
            $remainingUnpaidInterest = round($interestDue - $interestPayment, 2);
        }
        $principalPayment = round($remainingPayment - $interestPayment, 2);
        $loanReceivablesAccount = $loan->loan_account;
        $loanInterestsAccount = Account::whereAccountableType(LoanType::class)->whereAccountableId($loan->loan_type_id)->whereTag('loan_interests')->firstOrFail();

        if ($surchargePayment > 0) {
            app(CreateTransaction::class)->handle(new TransactionData(
                account_id: Account::getFinesPenaltiesSurcharges()->id,
                transactionType: $transactionType,
                reference_number: $loanPaymentData->reference_number,
                payment_type_id: $loanPaymentData->payment_type_id,
                credit: round($surchargePayment, 2),
                member_id: $loan->member_id,
                remarks: 'Member Loan Payment Surcharge',
                transaction_date: $loanPaymentData->transaction_date,
                from_billing_type: $loanPaymentData->from_billing_type
            ));
        }
        if ($principalPayment > 0) {
            app(CreateTransaction::class)->handle(new TransactionData(
                account_id: $loanReceivablesAccount->id,
                transactionType: $transactionType,
                reference_number: $loanPaymentData->reference_number,
                payment_type_id: $loanPaymentData->payment_type_id,
                credit: round($principalPayment, 2),
                member_id: $loan->member_id,
                remarks: 'Member Loan Payment Principal',
                transaction_date: $loanPaymentData->transaction_date,
                from_billing_type: $loanPaymentData->from_billing_type
            ));
            $loan->update([
                'outstanding_balance' => $loan->outstanding_balance - $principalPayment,
            ]);
        }
        if ($interestPayment > 0) {
            app(CreateTransaction::class)->handle(new TransactionData(
                account_id: $loanInterestsAccount->id,
                transactionType: $transactionType,
                reference_number: $loanPaymentData->reference_number,
                payment_type_id: $loanPaymentData->payment_type_id,
                credit: round($interestPayment, 2),
                member_id: $loan->member_id,
                remarks: 'Member Loan Payment Interest',
                transaction_date: $loanPaymentData->transaction_date,
                from_billing_type: $loanPaymentData->from_billing_type
            ));
        }

        $loan->payments()->update([
            'unpaid_interest' => 0,
        ]);

        return LoanPayment::create([
            'loan_id' => $loan->id,
            'loan_billing_id' => $loanPaymentData->loan_billing_id,
            'member_id' => $loan->member_id,
            'buy_out' => $loanPaymentData->buy_out,
            'payment_type_id' => $loanPaymentData->payment_type_id,
            'amount' => $loanPaymentData->amount,
            'surcharge_payment' => $surchargePayment,
            'interest_payment' => $interestPayment,
            'principal_payment' => $principalPayment,
            'unpaid_interest' => $remainingUnpaidInterest ?? 0,
            'reference_number' => $loanPaymentData->reference_number,
            'remarks' => $loanPaymentData->remarks,
            'transaction_date' => $loanPaymentData->transaction_date,
        ]);
    }
}
