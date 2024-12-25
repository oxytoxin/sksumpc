<?php

namespace App\Console\Commands;

use App\Actions\CapitalSubscription\CreateNewCapitalSubscriptionAccount;
use App\Actions\Imprests\CreateNewImprestsAccount;
use App\Actions\LoveGifts\CreateNewLoveGiftsAccount;
use App\Actions\MSO\DepositToMsoAccount;
use App\Actions\Savings\CreateNewSavingsAccount;
use App\Actions\TimeDeposits\CreateTimeDeposit;
use App\Enums\MsoType;
use App\Models\Member;
use App\Models\TransactionType;
use App\Oxytoxin\DTO\MSO\Accounts\CapitalSubscriptionAccountData;
use App\Oxytoxin\DTO\MSO\Accounts\ImprestAccountData;
use App\Oxytoxin\DTO\MSO\Accounts\LoveGiftAccountData;
use App\Oxytoxin\DTO\MSO\Accounts\SavingsAccountData;
use App\Oxytoxin\DTO\MSO\TimeDepositData;
use App\Oxytoxin\DTO\Transactions\TransactionData;
use App\Oxytoxin\Providers\TimeDepositsProvider;
use Illuminate\Console\Command;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\LazyCollection;
use Spatie\SimpleExcel\SimpleExcelReader;

class ImportMemberAccounts extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:import-member-accounts';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Import member accounts';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $members = Member::get(['id', 'mpc_code']);
        $members = $members->mapWithKeys(fn ($m) => [$m->mpc_code => $m]);
        $transaction_type = TransactionType::JEV();
        // $rows = SimpleExcelReader::create(storage_path('csv/accounts/regular_savings_accounts.xlsx'))->getRows();
        // $this->importSavings(rows: $rows, members: $members, transaction_type: $transaction_type);
        // $rows = SimpleExcelReader::create(storage_path('csv/accounts/associate_savings_accounts.xlsx'))->getRows();
        // $this->importSavings(rows: $rows, members: $members, transaction_type: $transaction_type);
        // $rows = SimpleExcelReader::create(storage_path('csv/accounts/laboratory_savings_accounts.xlsx'))->getRows();
        // $this->importSavings(rows: $rows, members: $members, transaction_type: $transaction_type);
        // $rows = SimpleExcelReader::create(storage_path('csv/accounts/imprest_accounts.xlsx'))->getRows();
        // $this->importImprests(rows: $rows, members: $members, transaction_type: $transaction_type);
        // $rows = SimpleExcelReader::create(storage_path('csv/accounts/time_deposit_accounts.xlsx'))->getRows();
        // $this->importTimeDeposits(rows: $rows, members: $members, transaction_type: $transaction_type);
        Member::doesntHave('capital_subscription_account')->each(function ($member) {
            app(CreateNewCapitalSubscriptionAccount::class)->handle(new CapitalSubscriptionAccountData(
                member_id: $member->id,
                name: $member->full_name ?? ($member->first_name.' '.$member->last_name)
            ));
        });
        Member::doesntHave('savings_accounts')->each(function ($member) {
            app(CreateNewSavingsAccount::class)->handle(new SavingsAccountData(
                member_id: $member->id,
                name: $member->full_name ?? ($member->first_name.' '.$member->last_name)
            ));
        });
        Member::doesntHave('imprest_account')->each(function ($member) {
            app(CreateNewImprestsAccount::class)->handle(new ImprestAccountData(
                member_id: $member->id,
                name: $member->full_name ?? ($member->first_name.' '.$member->last_name)
            ));
        });
        Member::doesntHave('love_gift_account')->each(function ($member) {
            app(CreateNewLoveGiftsAccount::class)->handle(new LoveGiftAccountData(
                member_id: $member->id,
                name: $member->full_name ?? ($member->first_name.' '.$member->last_name)
            ));
        });
    }

    private function importSavings(LazyCollection $rows, Collection $members, TransactionType $transaction_type)
    {
        $rows->each(function ($data) use ($members, $transaction_type) {
            $member = $members[$data['mpc_code']] ?? null;
            if ($member) {
                $account = app(CreateNewSavingsAccount::class)->handle(new SavingsAccountData(
                    member_id: $member->id,
                    name: $data['name'],
                    number: $data['account_number'],
                ));
                app(DepositToMsoAccount::class)->handle(MsoType::SAVINGS, new TransactionData(
                    account_id: $account->id,
                    transactionType: $transaction_type,
                    reference_number: '#BALANCEFORWARDED',
                    payment_type_id: 1,
                    credit: $data['amount'],
                    member_id: $member->id,
                    transaction_date: '12/31/2024',
                    payee: $member->full_name,
                ));
            }
        });
    }

    private function importImprests(LazyCollection $rows, Collection $members, TransactionType $transaction_type)
    {
        $rows->each(function ($data) use ($members, $transaction_type) {
            $member = $members[$data['mpc_code']] ?? null;
            if ($member) {
                $account = app(CreateNewImprestsAccount::class)->handle(new ImprestAccountData(
                    member_id: $member->id,
                    name: $data['name'],
                    number: $data['account_number']
                ));
                app(DepositToMsoAccount::class)->handle(MsoType::IMPREST, new TransactionData(
                    account_id: $account->id,
                    transactionType: $transaction_type,
                    payment_type_id: 1,
                    reference_number: '#BALANCEFORWARDED',
                    credit: $data['amount'],
                    member_id: $member->id,
                    transaction_date: '12/31/2024',
                    payee: $member->full_name,
                ));
            }
        });
    }

    private function importTimeDeposits(LazyCollection $rows, Collection $members, TransactionType $transaction_type)
    {
        $rows->each(function ($data) use ($members, $transaction_type) {
            $member = $members[$data['mpc_code']] ?? null;
            if ($member) {
                app(CreateTimeDeposit::class)->handle(new TimeDepositData(
                    member_id: $member->id,
                    maturity_date: TimeDepositsProvider::getMaturityDate($data['transaction_date']),
                    payment_type_id: 1,
                    reference_number: '#BALANCEFORWARDED',
                    amount: $data['amount'],
                    maturity_amount: TimeDepositsProvider::getMaturityAmount($data['amount']),
                    transaction_date: $data['transaction_date']->format('m/d/Y')
                ), $transaction_type, $data['account_number']);
            }
        });
    }
}
