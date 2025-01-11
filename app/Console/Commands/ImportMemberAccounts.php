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
        $members = $members->mapWithKeys(fn($m) => [$m->mpc_code => $m]);
        $transaction_type = TransactionType::JEV();
        Member::doesntHave('capital_subscription_account')->each(function ($member) {
            app(CreateNewCapitalSubscriptionAccount::class)->handle(new CapitalSubscriptionAccountData(
                member_id: $member->id,
                name: $member->full_name ?? ($member->first_name . ' ' . $member->last_name)
            ));
        });
        Member::doesntHave('savings_accounts')->each(function ($member) {
            app(CreateNewSavingsAccount::class)->handle(new SavingsAccountData(
                member_id: $member->id,
                name: $member->full_name ?? ($member->first_name . ' ' . $member->last_name)
            ));
        });
        Member::doesntHave('imprest_account')->each(function ($member) {
            app(CreateNewImprestsAccount::class)->handle(new ImprestAccountData(
                member_id: $member->id,
                name: $member->full_name ?? ($member->first_name . ' ' . $member->last_name)
            ));
        });
        Member::doesntHave('love_gift_account')->each(function ($member) {
            app(CreateNewLoveGiftsAccount::class)->handle(new LoveGiftAccountData(
                member_id: $member->id,
                name: $member->full_name ?? ($member->first_name . ' ' . $member->last_name)
            ));
        });
    }
}
