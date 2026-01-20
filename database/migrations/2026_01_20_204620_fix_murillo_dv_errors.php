<?php

    use App\Actions\Loans\RecomputeLoan;
    use App\Actions\Transactions\CreateTransaction;
    use App\Models\Loan;
    use App\Models\LoanPayment;
    use App\Models\Transaction;
    use App\Models\TransactionType;
    use App\Oxytoxin\DTO\Transactions\TransactionData;
    use Illuminate\Database\Migrations\Migration;
    use Illuminate\Database\Schema\Blueprint;
    use Illuminate\Support\Facades\Schema;

    return new class extends Migration {
        public function up(): void
        {
            Transaction::whereIn('id', [68348, 68351, 68354, 68357])->delete();
            LoanPayment::find(8128)->update([
                'amount' => 41500
            ]);
            LoanPayment::find(8125)->update([
                'amount' => 110003.09
            ]);

            RecomputeLoan::handle(Loan::find(636));
            RecomputeLoan::handle(Loan::find(690));

            app(CreateTransaction::class)->handle(new TransactionData(
                account_id: 204,
                transactionType: TransactionType::CDJ(),
                reference_number: 'CV-OTH 25-12-3336',
                payment_type_id: 6,
                credit: 41500,
                member_id: 394,
                remarks: 'Member Loan Payment Interest',
                transaction_date: '2025-12-23'
            ));


            app(CreateTransaction::class)->handle(new TransactionData(
                account_id: Loan::find(690)->loan_account->id,
                transactionType: TransactionType::CDJ(),
                reference_number: 'CV-OTH 25-12-3336',
                payment_type_id: 6,
                credit: 109966.42,
                member_id: 394,
                remarks: 'Member Loan Payment Principal',
                transaction_date: '2025-12-23'
            ));

            app(CreateTransaction::class)->handle(new TransactionData(
                account_id: 206,
                transactionType: TransactionType::CDJ(),
                reference_number: 'CV-OTH 25-12-3336',
                payment_type_id: 6,
                credit: 36.67,
                member_id: 394,
                remarks: 'Member Loan Payment Interest',
                transaction_date: '2025-12-23'
            ));
        }
    };
