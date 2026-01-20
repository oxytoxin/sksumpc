<?php

    namespace App\Actions\Loans;

    use App\Models\Loan;
    use App\Models\LoanPayment;
    use App\Models\Transaction;
    use App\Oxytoxin\Providers\LoansProvider;

    class RecomputeLoan
    {

        public static function handle(Loan $loan): void
        {
            $simulated_payments = self::getSimulatedPayments($loan);
            $actual_payments = $loan->payments;
            foreach ($actual_payments as $key => $actual_payment) {
                if ($actual_payment->reference_number == '#BALANCEFORWARDED') {
                    continue;
                }

                $principal_payment_transaction = Transaction::query()
                    ->where('reference_number', $actual_payment->reference_number)
                    ->where('member_id', $actual_payment->member_id)
                    ->where('transaction_date', $actual_payment->transaction_date)
                    ->where('credit', $actual_payment->principal_payment)
                    ->where('remarks', 'Member Loan Payment Principal')
                    ->first();
                $interest_payment_transaction = Transaction::query()
                    ->where('reference_number', $actual_payment->reference_number)
                    ->where('member_id', $actual_payment->member_id)
                    ->where('transaction_date', $actual_payment->transaction_date)
                    ->where('credit', $actual_payment->interest_payment)
                    ->where('remarks', 'Member Loan Payment Interest')
                    ->first();
                $cash_transaction = Transaction::query()
                    ->where('reference_number', $actual_payment->reference_number)
                    ->where('member_id', $actual_payment->member_id)
                    ->where('transaction_date', $actual_payment->transaction_date)
                    ->whereIn('account_id', [2, 4])
                    ->whereNull('credit')
                    ->where('debit', $actual_payment->amount)
                    ->first();


                $actual_payment->principal_payment = $simulated_payments[$key]->principal_payment;
                $actual_payment->interest_payment = $simulated_payments[$key]->interest_payment;
                $loan->outstanding_balance = $simulated_payments[$key]->balance;


                if ($principal_payment_transaction) {
                    $principal_payment_transaction->credit = $actual_payment->principal_payment;
                    $principal_payment_transaction->save();
                }

                if ($interest_payment_transaction) {
                    $interest_payment_transaction->credit = $actual_payment->interest_payment;
                    $interest_payment_transaction->save();
                }

                if ($cash_transaction) {
                    $cash_transaction->debit = $actual_payment->amount;
                    $cash_transaction->save();
                }

                $actual_payment->save();
            }
            $loan->save();
        }

        public static function getSimulatedPayments(Loan $loan): array
        {
            $payments = [];
            $balance = $loan->gross_amount;
            $previous_date = $loan->transaction_date;
            $unpaid_interest = 0;
            foreach ($loan->payments as $loan_payment) {
                if ($loan_payment->reference_number == '#BALANCEFORWARDED') {
                    $interest = 0;
                } else {
                    $interest = round(LoansProvider::computeAccruedInterestFromDates($loan, $balance, $previous_date, $loan_payment->transaction_date), 2) + $unpaid_interest;
                }


                $unpaid_interest = 0;
                if ($loan_payment->amount < $interest) {
                    $principal = 0;
                    $interest = round($loan_payment->amount, 2);
                    $unpaid_interest = $interest - $loan_payment->amount;
                }
                $principal = $loan_payment->amount - $interest;
                $balance -= $principal;
                $previous_date = $loan_payment->transaction_date;
                $payment = new LoanPayment([
                    'id' => $loan_payment->id,
                    'amount' => $loan_payment->amount,
                    'reference_number' => $loan_payment->reference_number,
                    'principal_payment' => $principal,
                    'interest_payment' => $interest,
                    'balance' => round($balance, 2),
                    'unpaid_interest' => $unpaid_interest,
                    'transaction_date' => $loan_payment->transaction_date,
                ]);
                $payments[] = $payment;
            }
            return $payments;
        }
    }