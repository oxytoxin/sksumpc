<?php

namespace Database\Seeders;

use App\Models\Account;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class LiabilitiesAccountsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Account::create(["account_type_id" => 2, "name" => "CURRENT LIABILITIES", "number" => "21000", "children" => [
            [
                "account_type_id" => 2, "name" => "DEPOSIT LIABILITIES", "number" => "21100", "children" => [
                    ["account_type_id" => 2, "name" => "SAVINGS DEPOSIT", "number" => "21110"],
                    ["account_type_id" => 2, "name" => "TIME DEPOSIT", "number" => "21112"],
                    ["account_type_id" => 2, "name" => "OTHER DEPOSIT LIABILITIES", "number" => "21113"],
                ],
            ],
            ["account_type_id" => 2, "name" => "ACCOUNTS PAYABLE", "number" => "21210"],
            ["account_type_id" => 2, "name" => "LOANS PAYABLE", "number" => "21230"],
            ["account_type_id" => 2, "name" => "OTHER PAYABLES", "number" => "21290"],
            ["account_type_id" => 2, "name" => "SSS/ECC/PHILHEALTH/PAG-IBIG PREMIUM CONTRIBUTIONS PAYABLE", "number" => "21320"],
            ["account_type_id" => 2, "name" => "SSS/PAGIBIG LOANS PAYABLE", "number" => "21330"],
            ["account_type_id" => 2, "name" => "INTEREST ON SHARE CAPITAL PAYABLE", "number" => "21440"],
            ["account_type_id" => 2, "name" => "PATRONAGE REFUND PAYABLE", "number" => "21450"],
            ["account_type_id" => 2, "name" => "DUE TO UNION/FEDERATION (CETF)", "number" => "21460"],
            ["account_type_id" => 2, "name" => "OTHER CURRENT LIABILITIES", "number" => "21490"],
        ]]);

        Account::create(["account_type_id" => 2, "name" => "NON-CURRENT LIABILITIES", "number" => "21000", "children" => [
            ["account_type_id" => 2, "name" => "RETIREMENT PAYABLE", "number" => "22400"],
            ["account_type_id" => 2, "name" => "MEMBERS BENEFIT AND OTHER FUND PAYABLE", "number" => "24120"],
            ["account_type_id" => 2, "name" => "WITHHOLDING TAX PAYABLE", "number" => "21340"],
        ]]);
    }
}
