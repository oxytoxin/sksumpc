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
        Account::create(["account_type_id" => 4, "name" => "PAID UP SHARE CAPITAL", "number" => "30000", "children" => [
            ["account_type_id" => 4, "name" => "COMMON", "number" => "30130-0001", "tag" => "member_common_cbu_paid"],
            ["account_type_id" => 4, "name" => "LABORATORY", "number" => "30130-0002", "tag" => "member_laboratory_cbu_paid"],
            ["account_type_id" => 4, "name" => "PREFERRED", "number" => "30230-0001", "tag" => "member_preferred_cbu_paid"],
        ]]);
        Account::create(["account_type_id" => 4, "name" => "DEPOSIT FOR SHARE CAPITAL", "number" => "30300", "children" => [
            ["account_type_id" => 4, "name" => "COMMON", "number" => "30300-0001", "tag" => "member_common_cbu_deposit"],
            ["account_type_id" => 4, "name" => "LABORATORY", "number" => "30300-0002", "tag" => "member_laboratory_cbu_deposit"],
            ["account_type_id" => 4, "name" => "PREFERRED", "number" => "30300-0003", "tag" => "member_preferred_cbu_deposit"],
        ]]);
        Account::create(["account_type_id" => 4, "name" => "UNDIVIDED NET SURPLUS", "number" => "30600"]);
        Account::create(["account_type_id" => 4, "name" => "STATUTORY FUND", "number" => "30800", "children" => [
            ["account_type_id" => 4, "name" => "RESERVE FUND", "number" => "30810"],
            ["account_type_id" => 4, "name" => "COOP. EDUCATION & TRAINING FUND", "number" => "30820"],
            ["account_type_id" => 4, "name" => "COMMUNITY DEVELOPMENT FUND", "number" => "30830"],
            ["account_type_id" => 4, "name" => "OPTIONAL FUND", "number" => "30840"],
        ]]);
    }
}
