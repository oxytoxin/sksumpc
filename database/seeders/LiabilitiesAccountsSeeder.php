<?php

namespace Database\Seeders;

use App\Models\Account;
use Illuminate\Database\Seeder;

class LiabilitiesAccountsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Account::create(['account_type_id' => 2, 'name' => 'CURRENT LIABILITIES', 'number' => '21000', 'children' => [
            [
                'account_type_id' => 2, 'name' => 'DEPOSIT LIABILITIES', 'number' => '21100', 'children' => [
                    ['account_type_id' => 2, 'name' => 'SAVINGS DEPOSIT', 'number' => '21110', 'tag' => 'member_savings'],
                    ['account_type_id' => 2, 'name' => 'TIME DEPOSIT', 'number' => '21112', 'tag' => 'member_time_deposits'],
                    ['account_type_id' => 2, 'name' => 'OTHER DEPOSIT LIABILITIES', 'number' => '21113'],
                ],
            ],
            ['account_type_id' => 2, 'name' => 'ACCOUNTS PAYABLE', 'number' => '21210'],
            ['account_type_id' => 2, 'name' => 'LOANS PAYABLE', 'number' => '21230', 'tag' => 'loans_payable'],
            ['account_type_id' => 2, 'name' => 'OTHER PAYABLES', 'number' => '21290'],
            ['account_type_id' => 2, 'name' => 'SSS/ECC/PHILHEALTH/PAG-IBIG PREMIUM CONTRIBUTIONS PAYABLE', 'number' => '21320'],
            ['account_type_id' => 2, 'name' => 'SSS/PAGIBIG LOANS PAYABLE', 'number' => '21330'],
            ['account_type_id' => 2, 'name' => 'INTEREST ON SHARE CAPITAL PAYABLE', 'number' => '21440'],
            ['account_type_id' => 2, 'name' => 'PATRONAGE REFUND PAYABLE', 'number' => '21450'],
            ['account_type_id' => 2, 'name' => 'DUE TO UNION/FEDERATION (CETF)', 'number' => '21460'],
            ['account_type_id' => 2, 'name' => 'OTHER CURRENT LIABILITIES', 'number' => '21490', 'children' => [
                ['account_type_id' => 2, 'name' => 'LOAN INSURANCE', 'number' => '21490-0001'],
                ['account_type_id' => 2, 'name' => 'LOAN REFUNDS', 'number' => '21490-0002'],
                ['account_type_id' => 2, 'name' => 'FAMILY INSURANCE', 'number' => '21490-0003'],
                ['account_type_id' => 2, 'name' => 'OTHER ACCOUNTS', 'number' => '21490-0004'],
            ]],
        ]]);

        Account::create(['account_type_id' => 2, 'name' => 'NON-CURRENT LIABILITIES', 'number' => '21000', 'children' => [
            ['account_type_id' => 2, 'name' => 'EMPLOYEES RETIREMENT FUND PAYABLE', 'number' => '22400'],
            ['account_type_id' => 2, 'name' => 'MEMBERS BENEFIT FUND PAYABLE', 'number' => '24120'],
            ['account_type_id' => 2, 'name' => 'WITHHOLDING TAX PAYABLE', 'number' => '21340'],
        ]]);
    }
}
