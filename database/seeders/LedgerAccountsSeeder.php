<?php

namespace Database\Seeders;

use App\Models\LedgerAccount;
use DB;
use Illuminate\Database\Seeder;
use Spatie\SimpleExcel\SimpleExcelReader;

class LedgerAccountsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::beginTransaction();
        $rows = SimpleExcelReader::create(storage_path('csv/chart_of_accounts.csv'))->getRows();
        $rows->each(function ($account) {
            LedgerAccount::create([
                'ledger_account_category_id' => $account['CATEGORY_ID'],
                'title' => $account['TITLE'],
                'code' => $account['CODE'],
            ]);
        });
        DB::commit();
    }
}
