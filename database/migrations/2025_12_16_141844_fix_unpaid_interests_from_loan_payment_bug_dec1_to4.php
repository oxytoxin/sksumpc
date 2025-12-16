<?php

    use App\Actions\Transactions\CreateTransaction;
    use App\Models\Account;
    use App\Models\LoanPayment;
    use App\Models\LoanType;
    use App\Models\Transaction;
    use App\Models\TransactionType;
    use App\Oxytoxin\DTO\Transactions\TransactionData;
    use Illuminate\Database\Migrations\Migration;
    use Illuminate\Database\Schema\Blueprint;
    use Illuminate\Support\Facades\Schema;

    return new class extends Migration {
        public function up(): void
        {
            DB::beginTransaction();
            $loan_payments = [7996, 7997, 7998, 7999, 8000, 8001, 8002, 8003, 8005];
            $loan_payments = LoanPayment::query()->with('loan')->whereIn('id', $loan_payments)->get();
            $transactionType = TransactionType::CRJ();
            foreach ($loan_payments as $loan_payment) {
                if ($loan_payment->unpaid_interest > 0) {
                    $principal_payment = Transaction::query()
                        ->where('reference_number', $loan_payment->reference_number)
                        ->where('member_id', $loan_payment->member_id)
                        ->where('transaction_date', $loan_payment->transaction_date)
                        ->where('credit', $loan_payment->principal_payment)
                        ->where('remarks', 'Member Loan Payment Principal')
                        ->first();
                    $principal_payment->update([
                        'credit' => $principal_payment->credit - $loan_payment->unpaid_interest
                    ]);
                    $loan_interests_account_id = Account::whereAccountableType(LoanType::class)->whereAccountableId($loan_payment->loan->loan_type_id)->whereTag('loan_interests')->first()->id;

                    app(CreateTransaction::class)->handle(new TransactionData(
                        account_id: $loan_interests_account_id,
                        transactionType: $transactionType,
                        reference_number: $loan_payment->reference_number,
                        payment_type_id: 1,
                        credit: $loan_payment->unpaid_interest,
                        member_id: $loan_payment->member_id,
                        remarks: 'Member Loan Payment Interest',
                        transaction_date: $loan_payment->transaction_date,
                    ));

                    $loan_payment->loan->update([
                        'outstanding_balance' => $loan_payment->loan->outstanding_balance + $loan_payment->unpaid_interest
                    ]);
                    $loan_payment->update([
                        'interest_payment' => $loan_payment->unpaid_interest,
                        'principal_payment' => $loan_payment->principal_payment - $loan_payment->unpaid_interest,
                        'unpaid_interest' => 0
                    ]);

                }
                DB::commit();
            }
        }
    };
