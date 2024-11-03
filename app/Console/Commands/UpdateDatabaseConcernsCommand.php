<?php

namespace App\Console\Commands;

use App\Models\User;
use App\Models\Member;
use App\Models\Account;
use App\Models\Division;
use App\Models\MemberType;
use App\Models\Occupation;
use App\Models\PaymentType;
use App\Models\Transaction;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Spatie\Permission\Models\Role;
use Illuminate\Support\Facades\Hash;
use App\Models\DisbursementVoucherItem;

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

        DisbursementVoucherItem::each(function ($item) {
            $item->update([
                'transaction_date' => $item->disbursement_voucher->transaction_date,
            ]);
        });

        \DB::commit();
    }
}
