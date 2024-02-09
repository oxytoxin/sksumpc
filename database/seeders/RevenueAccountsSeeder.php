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
            ["account_type_id" => 3, "name" => "LOANS", "number" => "40120-001"],
            ["account_type_id" => 3, "name" => "OTHERS", "number" => "40120-001"]
        ]]);
        Account::create(["account_type_id" => 3, "name" => "FINES, PENALTIES, SURCHARGES", "number" => "40140"]);
        Account::create(["account_type_id" => 3, "name" => "RESERVATION FEES (DORM)", "number" => "40660"]);
        Account::create(["account_type_id" => 3, "name" => "MEMBERSHIP FEES", "number" => "40620"]);
        Account::create(["account_type_id" => 3, "name" => "MISCELLANEOUS INCOME", "number" => "40700"]);
        Account::create(["account_type_id" => 3, "name" => "COMMISSION INCOME", "number" => "40630"]);
        Account::create(["account_type_id" => 3, "name" => "INTEREST INCOME FROM DEPOSITS", "tag" => "deposit_interests", "number" => "40610", "children" => [
            ["account_type_id" => 3, "name" => "DBP (GENERAL FUND)", "number" => "40610-001"],
            ["account_type_id" => 3, "name" => "DBP (MSO FUND)", "number" => "40610-002"],
            ["account_type_id" => 3, "name" => "LBP", "number" => "40610-003"],
            ["account_type_id" => 3, "name" => "LBP SKSU MPC STATUTORY FUNDS", "number" => "40610-004"],
            ["account_type_id" => 3, "name" => "DBP TIME DEPOSIT", "number" => "40610-005"],
        ]]);
        Account::create(["account_type_id" => 3, "name" => "INTEREST INCOME FROM TREASURY BILLS", "number" => "40640"]);
        Account::create(["account_type_id" => 3, "name" => "OTHER INCOME", "number" => "40600", "tag" => "other_income", "children" => [
            ["account_type_id" => 3, "name" => "INVESTMENT", "number" => "40600-001"],
            ["account_type_id" => 3, "name" => "PORTA CEILI", "number" => "40600-001"],
            ["account_type_id" => 3, "name" => "DORMITORY", "number" => "40600-001"],
        ]]);
        Account::create(["account_type_id" => 3, "name" => "FILING FEES", "number" => "40130"]);
        Account::create(["account_type_id" => 3, "name" => "SERVICE INCOME", "number" => "40210"]);
        Account::create(["account_type_id" => 3, "name" => "RENTAL INCOME", "number" => "40650"]);
        Account::create(["account_type_id" => 3, "name" => "PURCHASES", "number" => "51110"]);
    }
}
