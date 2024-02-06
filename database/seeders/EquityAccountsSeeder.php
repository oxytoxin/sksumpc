<?php

namespace Database\Seeders;

use App\Models\Account;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class EquityAccountsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Account::create(["account_type_id" => 4, "name" => "PAID UP SHARE CAPITAL- COMMON", "number" => "30130"]);
        Account::create(["account_type_id" => 4, "name" => "PAID UP SHARE CAPITAL- PREFERRED", "number" => "30230"]);
        Account::create(["account_type_id" => 4, "name" => "DEPOSIT FOR SHARE CAPITAL", "number" => "30300"]);
        Account::create(["account_type_id" => 4, "name" => "UNDIVIDED NET SURPLUS", "number" => "30600"]);
        Account::create(["account_type_id" => 4, "name" => "RESERVE FUND", "number" => "30810"]);
        Account::create(["account_type_id" => 4, "name" => "COOP. EDUCATION & TRAINING FUND", "number" => "30820"]);
        Account::create(["account_type_id" => 4, "name" => "COMMUNITY DEVELOPMENT FUND", "number" => "30830"]);
        Account::create(["account_type_id" => 4, "name" => "OPTIONAL FUND", "number" => "30840"]);
    }
}
