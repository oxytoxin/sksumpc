<?php

    namespace App\Actions\Loans;

    use App\Actions\Transactions\CreateTransaction;
    use App\Enums\PaymentTypes;
    use App\Models\Account;
    use App\Models\LoanAccount;
    use App\Models\LoanPayment;
    use App\Models\LoanType;
    use App\Oxytoxin\DTO\Transactions\TransactionData;

    class PayLegacyLoan
    {
        public function handle(LoanAccount $loanAccount, $principal, $interest, $payment_type_id, $reference_number, $transaction_date, $transactionType): LoanPayment
        {
            $loan = $loanAccount->loan;
            $loan_receivables_account = $loan->loan_account;
            $loan_interests_account = Account::whereAccountableType(LoanType::class)->whereAccountableId($loan->loan_type_id)->whereTag('loan_interests')->first();

            if ($principal) {
                app(CreateTransaction::class)->handle(new TransactionData(
                    account_id: $loan_receivables_account->id,
                    transactionType: $transactionType,
                    reference_number: $reference_number,
                    payment_type_id: $payment_type_id,
                    credit: $principal,
                    member_id: $loan->member_id,
                    remarks: 'Member Loan Payment Principal',
                    transaction_date: $transaction_date
                ));
            }
            if ($interest) {
                app(CreateTransaction::class)->handle(new TransactionData(
                    account_id: $loan_interests_account->id,
                    transactionType: $transactionType,
                    reference_number: $reference_number,
                    payment_type_id: $payment_type_id,
                    credit: $interest,
                    member_id: $loan->member_id,
                    remarks: 'Member Loan Payment Interest',
                    transaction_date: $transaction_date
                ));
            }

            $cash_in_bank_account_id = Account::getCashInBankGF()->id;
            $cash_on_hand_account_id = Account::getCashOnHand()->id;

            $amount = 0;
            if ($principal) {
                $amount += $principal;
            }
            if ($interest) {
                $amount += $interest;
            }

            $data = new TransactionData(
                account_id: $cash_on_hand_account_id,
                transactionType: $transactionType,
                reference_number: $reference_number,
                payment_type_id: $payment_type_id,
                debit: round($amount, 2),
                member_id: $loan->member_id,
                remarks: 'Member Legacy Loan Payment',
                transaction_date: $transaction_date
            );

            if ($data->payment_type_id == PaymentTypes::ADA->value) {
                $data->account_id = $cash_in_bank_account_id;
            } else {
                $data->account_id = $cash_on_hand_account_id;
            }

            app(CreateTransaction::class)->handle($data);


            return $loan->payments()->create([
                'member_id' => $loan->member_id,
                'payment_type_id' => $payment_type_id,
                'amount' => round($amount, 2),
                'interest_payment' => $interest,
                'principal_payment' => $principal,
                'reference_number' => $reference_number,
                'transaction_date' => $transaction_date,
            ]);
        }
    }
