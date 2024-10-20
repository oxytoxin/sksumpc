<?php

namespace App\Actions\Loans;

use App\Actions\Transactions\CreateTransaction;
use App\Models\Account;
use App\Models\Loan;
use App\Models\LoanAccount;
use App\Models\LoanPayment;
use App\Models\LoanType;
use App\Models\TransactionType;
use App\Oxytoxin\DTO\Loan\LoanPaymentData;
use App\Oxytoxin\DTO\Transactions\TransactionData;
use App\Oxytoxin\Providers\LoansProvider;
use Lorisleiva\Actions\Concerns\AsAction;

class PayLegacyLoan
{
    use AsAction;

    public function handle(LoanAccount $loanAccount, $principal, $interest, $payment_type_id, $reference_number, $transaction_date, $transactionType, $isJevOrDv = false): LoanPayment
    {
        $loan = $loanAccount->loan;
        $loan_receivables_account = $loan->loan_account;
        $loan_interests_account = Account::whereAccountableType(LoanType::class)->whereAccountableId($loan->loan_type_id)->whereTag('loan_interests')->first();

        if ($transactionType->name == 'CRJ' && !$isJevOrDv)
            app(CreateTransaction::class)->handle(new TransactionData(
                account_id: Account::getCashOnHand()->id,
                transactionType: $transactionType,
                payment_type_id: $payment_type_id,
                reference_number: $reference_number,
                debit: $principal + $interest,
                member_id: $loan->member_id,
                remarks: 'Member Loan Payment',
                transaction_date: $transaction_date
            ));

        app(CreateTransaction::class)->handle(new TransactionData(
            account_id: $loan_receivables_account->id,
            transactionType: $transactionType,
            payment_type_id: $payment_type_id,
            reference_number: $reference_number,
            credit: $principal,
            member_id: $loan->member_id,
            remarks: 'Member Loan Payment Principal',
            transaction_date: $transaction_date
        ));

        app(CreateTransaction::class)->handle(new TransactionData(
            account_id: $loan_interests_account->id,
            transactionType: $transactionType,
            payment_type_id: $payment_type_id,
            reference_number: $reference_number,
            credit: $interest,
            member_id: $loan->member_id,
            remarks: 'Member Loan Payment Interest',
            transaction_date: $transaction_date
        ));

        return $loan->payments()->create([
            'member_id' => $loan->member_id,
            'payment_type_id' => $payment_type_id,
            'amount' => $principal + $interest,
            'interest_payment' => $interest,
            'principal_payment' => $principal,
            'reference_number' => $reference_number,
            'transaction_date' => $transaction_date,
        ]);
    }
}
