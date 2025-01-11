<?php

namespace App\Console\Commands;

use DB;
use App\Enums\MsoType;
use App\Models\Member;
use App\Models\TransactionType;
use Illuminate\Console\Command;
use App\Actions\MSO\DepositToMsoAccount;
use App\Oxytoxin\DTO\MSO\TimeDepositData;
use Spatie\SimpleExcel\SimpleExcelReader;
use App\Actions\TimeDeposits\CreateTimeDeposit;
use App\Actions\Savings\CreateNewSavingsAccount;
use App\Oxytoxin\Providers\TimeDepositsProvider;
use App\Actions\Imprests\CreateNewImprestsAccount;
use App\Oxytoxin\DTO\Transactions\TransactionData;
use App\Oxytoxin\DTO\MSO\Accounts\ImprestAccountData;
use App\Oxytoxin\DTO\MSO\Accounts\SavingsAccountData;

class ImportMso extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:import-mso';

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
        $members = Member::get()->mapWithKeys(fn($m) => [$m->mpc_code => $m]);
        $transactionType = TransactionType::JEV();
        $source = SimpleExcelReader::create(storage_path('csv/deployment/MSO DATA FORWARDED AS OF DECEMBER 2024 - FINAL.xlsx'));

        $rows = $source
            ->fromSheetName('REGULAR')
            ->getRows();
        $this->importMso($rows, $members, $transactionType, MsoType::SAVINGS);
        $rows = $source
            ->fromSheetName('ASSOCIATE')
            ->getRows();
        $this->importMso($rows, $members, $transactionType, MsoType::SAVINGS);
        $rows = $source
            ->fromSheetName('LABORATORY')
            ->getRows();
        $this->importMso($rows, $members, $transactionType, MsoType::SAVINGS);
        $rows = $source
            ->fromSheetName('IMPREST')
            ->getRows();
        $this->importMso($rows, $members, $transactionType, MsoType::IMPREST);
        $rows = $source
            ->fromSheetName('TIMEDEPOSIT')
            ->getRows();
        $this->importTimeDeposits($rows, $members, $transactionType);
        DB::commit();
    }

    private function importTimeDeposits($rows, $members, $transactionType)
    {
        $rows->each(function ($row) use ($members, $transactionType) {
            $member = $members[$row['mpc_code']] ?? null;
            if ($member) {
                app(CreateTimeDeposit::class)->handle(new TimeDepositData(
                    member_id: $member->id,
                    maturity_date: $row['maturity_date']->format('m/d/Y'),
                    payment_type_id: 2,
                    reference_number: '#BALANCEFORWARDED',
                    amount: $row['amount'],
                    maturity_amount: TimeDepositsProvider::getMaturityAmount($row['amount'], $row['interest_rate']),
                    transaction_date: $row['date_open']
                ), $transactionType, $row['tdc_number']);
            }
        });
    }

    private function importMso($rows, $members, $transactionType, MsoType $msoType)
    {
        $rows->each(function ($row) use ($members, $transactionType, $msoType) {
            if (filled($row['mpc_code'])) {
                $member = $members[$row['mpc_code']];
                if ($msoType == MsoType::SAVINGS)
                    $account = app(CreateNewSavingsAccount::class)->handle(new SavingsAccountData(
                        member_id: $member->id,
                        name: $row['name'],
                        number: $row['account_number'],
                    ));
                if ($msoType == MsoType::IMPREST)
                    $account = app(CreateNewImprestsAccount::class)->handle(new ImprestAccountData(
                        member_id: $member->id,
                        name: $row['name'],
                        number: $row['account_number']
                    ));
                app(DepositToMsoAccount::class)->handle($msoType, new TransactionData(
                    account_id: $account->id,
                    transactionType: $transactionType,
                    reference_number: '#BALANCEFORWARDED',
                    payment_type_id: 2,
                    credit: $row['amount'],
                    member_id: $member->id,
                    transaction_date: $row['last_transaction_date'],
                    payee: $member->full_name,
                ));
            }
        });
    }
}
