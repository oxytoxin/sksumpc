<?php

namespace App\Console\Commands;

use App\Models\Account;
use Illuminate\Console\Command;
use Schema;

class SetupCashAndCashEquivalentsAccount extends Command
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
    protected $description = 'Setup Cash and Cash Equivalents Accounts';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $account = Account::create(['account_type_id' => 1, 'name' => 'CASH AND CASH EQUIVALENTS', 'number' => '11100', 'sort' => 10]);
        Account::where('id', '<=', 12)->whereNull('parent_id')->update([
            'parent_id' => $account->id
        ]);
        Account::find(2)->update([
            'show_sum' => false
        ]);
        Account::find(31)->update([
            'sum_description' => 'NET'
        ]);
        Account::find(14)->update([
            'parent_id' => 13,
            'sort' => 1000
        ]);
        Account::find(16)->update([
            'parent_id' => 15,
            'sort' => 1000
        ]);
        Account::find(13)->update([
            'sum_description' => 'NET'
        ]);
        Account::find(15)->update([
            'sum_description' => 'NET'
        ]);
    }
}
