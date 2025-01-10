<?php

namespace App\Console\Commands;

use App\Models\Member;
use Illuminate\Console\Command;
use Spatie\SimpleExcel\SimpleExcelReader;
use App\Oxytoxin\DTO\Transactions\TransactionData;
use App\Actions\CapitalSubscription\PayCapitalSubscription;
use App\Actions\CapitalSubscription\CreateNewCapitalSubscription;
use App\Oxytoxin\DTO\CapitalSubscription\CapitalSubscriptionData;
use App\Oxytoxin\DTO\MSO\Accounts\CapitalSubscriptionAccountData;
use App\Actions\CapitalSubscription\CreateNewCapitalSubscriptionAccount;
use App\Models\TransactionType;
use DB;

class ImportCbu extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:import-cbu';

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
        $rows = SimpleExcelReader::create(storage_path('csv/deployment/CBU FORWARDED AS OF DECEMBER 2024.xlsx'))
            ->getRows();
        $members = Member::get()->mapWithKeys(fn($m) => [$m->mpc_code => $m]);
        $transactionType = TransactionType::JEV();
        $rows->each(function ($row) use ($members, $transactionType) {
            if (filled($row['mpc_code'])) {
                $member = $members[$row['mpc_code']];
                app(CreateNewCapitalSubscriptionAccount::class)->handle(new CapitalSubscriptionAccountData(
                    member_id: $member->id,
                    name: $member->full_name
                ));
                $monthly_payment = ($row['number_of_shares'] - $row['amount_paid']) / 36;
                if ($monthly_payment < 0) {
                    $monthly_payment = 0;
                }
                $cbu = app(CreateNewCapitalSubscription::class)->handle($member, new CapitalSubscriptionData(
                    number_of_terms: 36,
                    number_of_shares: $row['number_of_shares'],
                    initial_amount_paid: $row['amount_paid'],
                    monthly_payment: $monthly_payment,
                    amount_subscribed: round($row['number_of_shares'] * $row['par_value'], 2),
                    par_value: $row['par_value'],
                    is_common: true,
                    transaction_date: $row['date_subscribed']
                ));
                app(PayCapitalSubscription::class)->handle($cbu, new TransactionData(
                    account_id: $member->capital_subscription_account->id,
                    transactionType: $transactionType,
                    reference_number: '#BALANCEFORWARDED',
                    payment_type_id: 1,
                    credit: $row['amount_paid'],
                    member_id: $member->id,
                    transaction_date: $row['date_subscribed'],
                    payee: $member->full_name,
                ));
            }
        });
        DB::commit();
    }
}
