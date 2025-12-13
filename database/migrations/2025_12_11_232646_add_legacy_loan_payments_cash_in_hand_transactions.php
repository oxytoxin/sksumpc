<?php

    use App\Actions\Transactions\CreateTransaction;
    use App\Models\TransactionType;
    use App\Oxytoxin\DTO\Transactions\TransactionData;
    use Illuminate\Database\Migrations\Migration;
    use Illuminate\Database\Schema\Blueprint;
    use Illuminate\Support\Facades\Schema;
    use Spatie\SimpleExcel\SimpleExcelReader;
    use function Pest\Laravel\delete;

    return new class extends Migration {
        public function up(): void
        {
            DB::beginTransaction();
            // delete duplicate transactions without loan_payments record
            DB::table('transactions')->whereIn('id', [33408, 33409, 33410, 33411, 33606, 33607, 33608, 33609])->delete();
            $transactionType = TransactionType::CRJ();
            $rows = SimpleExcelReader::create(storage_path('csv/additional/legacy_payments.xlsx'))->getRows();
            $rows->each(function ($row) use ($transactionType) {
                $transaction = app(CreateTransaction::class)->handle(
                    new TransactionData(
                        account_id: 2,
                        transactionType: $transactionType,
                        reference_number: $row['reference_number'],
                        payment_type_id: 1, debit: $row['amount'],
                        member_id: $row['member_id'],
                        transaction_date: $row['date']
                    )
                );
            });

            DB::commit();
        }

    };
