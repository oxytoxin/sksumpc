<?php

namespace App\Console\Commands;

use App\Actions\Transactions\CreateTransaction;
use App\Models\Account;
use App\Models\TransactionType;
use App\Oxytoxin\DTO\Transactions\TransactionData;
use DB;
use Illuminate\Console\Command;
use Spatie\SimpleExcel\SimpleExcelReader;

class ImportBalanceForwarded2024 extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:import-balance-forwarded2024';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        DB::beginTransaction();
        $rows = SimpleExcelReader::create(storage_path('csv/deployment/FINANCIAL STATEMENT.xlsx'))->getRows();
        $transactionType = TransactionType::JEV();
        $accounts = Account::whereNull('member_id')->get()->mapWithKeys(fn($a) => [$a->number => $a]);
        $rows->each(function ($row) use ($transactionType, $accounts) {
            if (filled($row['ACCOUNT CODE'])) {
                app(CreateTransaction::class)->handle(new TransactionData(
                    account_id: $accounts[$row['ACCOUNT CODE']]->id,
                    transactionType: $transactionType,
                    reference_number: '#BALANCEFORWARDED',
                    payment_type_id: 2,
                    debit: $row['DEBIT'] == 1 ? round($row['ENDING BALANCE DECEMBER 2024'], 2) : null,
                    credit: $row['DEBIT'] == 0 ? round($row['ENDING BALANCE DECEMBER 2024'], 2) : null,
                    remarks: 'BALANCE FORWARDED 2024',
                    transaction_date: '12/31/2024',
                ));
            }
        });
        DB::commit();
    }
}
