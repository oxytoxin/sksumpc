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
            $unpaid_interest = $loan->payments()->sum('unpaid_interest');
            $interest_due = round(LoansProvider::computeAccruedInterest($loan, $loan->outstanding_balance, $total_days) + $unpaid_interest, 2);
            $interest_payment = min($loanPaymentData->amount, $interest_due);
            $interest_payment = min($interest_payment, 0);
            if ($interest_payment < $interest_due) {
                $remaining_unpaid_interest = round($interest_due - $interest_payment, 2);
            }
            $principal_payment = round($loanPaymentData->amount - $interest_payment, 2);
            $loan_receivables_account = $loan->loan_account;
            $loan_interests_account = Account::whereAccountableType(LoanType::class)->whereAccountableId($loan->loan_type_id)->whereTag('loan_interests')->first();

            if ($principal_payment > 0) {
                app(CreateTransaction::class)->handle(new TransactionData(
                    account_id: $loan_receivables_account->id,
                    transactionType: $transactionType,
                    reference_number: $loanPaymentData->reference_number,
                    payment_type_id: $loanPaymentData->payment_type_id,
                    credit: round($principal_payment, 2),
                    member_id: $loan->member_id,
                    remarks: 'Member Loan Payment Principal',
                    transaction_date: $loanPaymentData->transaction_date,
                    from_billing_type: $loanPaymentData->from_billing_type
                ));
            }
            if ($interest_payment > 0) {
                app(CreateTransaction::class)->handle(new TransactionData(
                    account_id: $loan_interests_account->id,
                    transactionType: $transactionType,
                    reference_number: $loanPaymentData->reference_number,
                    payment_type_id: $loanPaymentData->payment_type_id,
                    credit: round($interest_payment, 2),
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
                'member_id' => $loan->member_id,
                'buy_out' => $loanPaymentData->buy_out,
                'payment_type_id' => $loanPaymentData->payment_type_id,
                'amount' => $loanPaymentData->amount,
                'interest_payment' => $interest_payment,
                'principal_payment' => $principal_payment,
                'unpaid_interest' => $remaining_unpaid_interest ?? 0,
                'reference_number' => $loanPaymentData->reference_number,
                'remarks' => $loanPaymentData->remarks,
                'transaction_date' => $loanPaymentData->transaction_date,
            ]);
        }
    }
