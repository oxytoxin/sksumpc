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
        $accounts = Account::whereNull('member_id')->get()->mapWithKeys(fn ($a) => [$a->number => $a]);
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
        app(CreateTransaction::class)->handle(new TransactionData(
            account_id: 100,
            transactionType: $transactionType,
            reference_number: '#BALANCEFORWARDED',
            payment_type_id: 2,
            debit: 89012.48,
            credit: null,
            remarks: 'CBU PAID UP',
            transaction_date: '12/31/2024',
        ));
        app(CreateTransaction::class)->handle(new TransactionData(
            account_id: 104,
            transactionType: $transactionType,
            reference_number: '#BALANCEFORWARDED',
            payment_type_id: 2,
            debit: null,
            credit: 89012.48,
            remarks: 'CBU DEPOSIT',
            transaction_date: '12/31/2024',
        ));
        app(CreateTransaction::class)->handle(new TransactionData(
            account_id: 101,
            transactionType: $transactionType,
            reference_number: '#BALANCEFORWARDED',
            payment_type_id: 2,
            debit: 11158.24,
            credit: null,
            remarks: 'CBU PAID UP',
            transaction_date: '12/31/2024',
        ));
        app(CreateTransaction::class)->handle(new TransactionData(
            account_id: 105,
            transactionType: $transactionType,
            reference_number: '#BALANCEFORWARDED',
            payment_type_id: 2,
            debit: null,
            credit: 11158.24,
            remarks: 'CBU DEPOSIT',
            transaction_date: '12/31/2024',
        ));
        app(CreateTransaction::class)->handle(new TransactionData(
            account_id: 102,
            transactionType: $transactionType,
            reference_number: '#BALANCEFORWARDED',
            payment_type_id: 2,
            debit: 14461.23,
            credit: null,
            remarks: 'CBU PAID UP',
            transaction_date: '12/31/2024',
        ));
        app(CreateTransaction::class)->handle(new TransactionData(
            account_id: 106,
            transactionType: $transactionType,
            reference_number: '#BALANCEFORWARDED',
            payment_type_id: 2,
            debit: null,
            credit: 14461.23,
            remarks: 'CBU DEPOSIT',
            transaction_date: '12/31/2024',
        ));
        DB::commit();
    }
}
