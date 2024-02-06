<?php

namespace Database\Seeders;

use App\Models\Account;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class RevenueAccountsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Account::create(["account_type_id" => 3, "name" => "INTEREST INCOME FROM LOANS", "number" => "40110", "tag" => "loan_interests"]);
        Account::create(["account_type_id" => 3, "name" => "SERVICE FEES", "number" => "40120", "children" => [
            ["account_type_id" => 3, "name" => "LOANS", "number" => "40120-001",]
        ]]);
        Account::create(["account_type_id" => 3, "name" => "FILING FEES", "number" => "40130"]);
        Account::create(["account_type_id" => 3, "name" => "FINES, PENALTIES, SURCHARGES", "number" => "40140"]);
        Account::create(["account_type_id" => 3, "name" => "SERVICE INCOME", "number" => "40210"]);
        Account::create(["account_type_id" => 3, "name" => "OTHER INCOME", "number" => "40600"]);
        Account::create(["account_type_id" => 3, "name" => "INCOME/INTEREST FROM INVESTMENT/DEPOSITS", "number" => "40610"]);
        Account::create(["account_type_id" => 3, "name" => "MEMBERSHIP FEE", "number" => "40620"]);
        Account::create(["account_type_id" => 3, "name" => "COMMISSION INCOME", "number" => "40630"]);
        Account::create(["account_type_id" => 3, "name" => "RENTAL INCOME", "number" => "40650"]);
        Account::create(["account_type_id" => 3, "name" => "MISCELLANEOUS INCOME", "number" => "40700"]);
        Account::create(["account_type_id" => 3, "name" => "PURCHASES", "number" => "51110"]);
    }
}
