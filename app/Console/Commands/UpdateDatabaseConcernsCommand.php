<?php

namespace App\Console\Commands;

use App\Models\Account;
use App\Models\Division;
use App\Models\Member;
use App\Models\MemberType;
use App\Models\Occupation;
use App\Models\PaymentType;
use App\Models\Transaction;
use Illuminate\Console\Command;

class UpdateDatabaseConcernsCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:cca';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Update database with miscellaneous concerns.';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        \DB::beginTransaction();
//        MemberType::create([
//            'name' => 'ORGANIZATION',
//            'minimum_initial_payment' => 6500,
//            'default_amount_subscribed' => 25000,
//            'default_number_of_shares' => 50,
//            'par_value' => 500,
//            'surcharge_rate' => 0.01,
//            'initial_number_of_terms' => 12,
//            'additional_number_of_terms' => 36,
//        ]);
//        MemberType::find(1)->update([
//            'name' => 'REGULAR'
//        ]);
//        Member::whereMemberTypeId(1)->update([
//            'member_subtype_id' => 1,
//        ]);
//        Member::whereMemberTypeId(2)->update([
//            'member_subtype_id' => 2,
//            'member_type_id' => 1
//        ]);
//        Member::whereMemberTypeId(6)->update([
//            'member_subtype_id' => 3,
//            'member_type_id' => 1
//        ]);
//        Member::whereMemberTypeId(5)->update([
//            'member_subtype_id' => 4,
//            'member_type_id' => 1
//        ]);
//        MemberType::whereIn('id', [2, 5, 6])->delete();
//        Transaction::each(function ($transaction) {
//            $transaction->update([
//                'payee' => $transaction->member?->full_name ?? 'SKSU-MPC'
//            ]);
//        });

        Division::create([
            'name' => 'PALIMBANG'
        ]);
        Occupation::create([
            'name' => 'OTHERS'
        ]);
        \DB::commit();
    }
}
