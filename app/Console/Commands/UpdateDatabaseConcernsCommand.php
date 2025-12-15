<?php

namespace App\Console\Commands;

use DB;
use App\Models\DisbursementVoucherItem;
use App\Models\TransactionDateHistory;
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
        DB::beginTransaction();

        DisbursementVoucherItem::each(function ($item) {
            $item->update([
                'transaction_date' => $item->disbursement_voucher->transaction_date,
            ]);
        });

        TransactionDateHistory::create([
            'date' => '12/31/2024',
            'is_current' => true,
        ]);

        DB::commit();
    }
}
