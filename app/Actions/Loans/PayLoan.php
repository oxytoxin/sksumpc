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
        $start = $loan->last_payment?->transaction_date ?? $loan->transaction_date;
        $end = $loanPaymentData->transaction_date;
        $total_days = LoansProvider::getAccruableDays($start, $end);
        $interest_due = LoansProvider::computeAccruedInterest($loan, $loan->outstanding_balance, $total_days);
        $interest_payment = min($loanPaymentData->amount, $interest_due);
        $principal_payment = $loanPaymentData->amount - $interest_payment;
        $loan_receivables_account = $loan->loan_account;
        $loan_interests_account = Account::whereAccountableType(LoanType::class)->whereAccountableId($loan->loan_type_id)->whereTag('loan_interests')->first();

        app(CreateTransaction::class)->handle(new TransactionData(
            account_id: $loan_receivables_account->id,
            transactionType: $transactionType,
            payment_type_id: $loanPaymentData->payment_type_id,
            reference_number: $loanPaymentData->reference_number,
            credit: $principal_payment,
            member_id: $loan->member_id,
            remarks: 'Member Loan Payment Principal',
            transaction_date: $loanPaymentData->transaction_date,
        ));
        app(CreateTransaction::class)->handle(new TransactionData(
            account_id: $loan_interests_account->id,
            transactionType: $transactionType,
            payment_type_id: $loanPaymentData->payment_type_id,
            reference_number: $loanPaymentData->reference_number,
            credit: $interest_payment,
            member_id: $loan->member_id,
            remarks: 'Member Loan Payment Interest',
            transaction_date: $loanPaymentData->transaction_date
        ));

        return LoanPayment::create([
            'loan_id' => $loan->id,
            'member_id' => $loan->member_id,
            'buy_out' => $loanPaymentData->buy_out,
            'payment_type_id' => $loanPaymentData->payment_type_id,
            'amount' => $loanPaymentData->amount,
            'interest_payment' => $interest_payment,
            'principal_payment' => $principal_payment,
            'reference_number' => $loanPaymentData->reference_number,
            'remarks' => $loanPaymentData->remarks,
            'transaction_date' => $loanPaymentData->transaction_date,
        ]);
    }
}
