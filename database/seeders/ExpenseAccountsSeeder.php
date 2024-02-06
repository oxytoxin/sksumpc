<?php

namespace Database\Seeders;

use App\Models\Account;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class ExpenseAccountsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Account::create(["account_type_id" => 5, "name" => "INTEREST EXPENSE ON BORROWINGS", "number" => "71100"]);
        Account::create(["account_type_id" => 5, "name" => "INTEREST EXPENSE ON DEPOSITS", "number" => "71200"]);
        Account::create(["account_type_id" => 5, "name" => "OTHER FINANCING CHARGES", "number" => "71300"]);
        Account::create(["account_type_id" => 5, "name" => "SALARIES & WAGES", "number" => "72140"]);
        Account::create(["account_type_id" => 5, "name" => "INCENTIVE AND ALLOWANCES", "number" => "72150"]);
        Account::create(["account_type_id" => 5, "name" => "EMPLOYEES BENEFIT", "number" => "72160"]);
        Account::create(["account_type_id" => 5, "name" => "SSS,PHILHEALTH,ECC,PAG-IBIG PREMIUM CONTRIBUTION", "number" => "72170"]);
        Account::create(["account_type_id" => 5, "name" => "RETIREMENT BENEFIT EXPENSES", "number" => "72180"]);
        Account::create(["account_type_id" => 5, "name" => "COMMISSION EXPENSES", "number" => "72190"]);
        Account::create(["account_type_id" => 5, "name" => "ADVERTISING & PROMOTION", "number" => "72200"]);
        Account::create(["account_type_id" => 5, "name" => "PROFESSIONAL FEES", "number" => "72210"]);
        Account::create(["account_type_id" => 5, "name" => "POWER, LIGHT AND WATER", "number" => "72280"]);
        Account::create(["account_type_id" => 5, "name" => "TRAVEL AND TRANSPORTATION", "number" => "72290"]);
        Account::create(["account_type_id" => 5, "name" => "INSURANCE", "number" => "72300"]);
        Account::create(["account_type_id" => 5, "name" => "REPAIRS AND MAINTENANCE", "number" => "72310"]);
        Account::create(["account_type_id" => 5, "name" => "RENTALS", "number" => "72320"]);
        Account::create(["account_type_id" => 5, "name" => "TAXES, FEES AND CHARGES", "number" => "72330"]);
        Account::create(["account_type_id" => 5, "name" => "COMMUNICATION", "number" => "72340"]);
        Account::create(["account_type_id" => 5, "name" => "REPRESENTATION", "number" => "72350"]);
        Account::create(["account_type_id" => 5, "name" => "GAS, OIL & LUBRICANTS", "number" => "72360"]);
        Account::create(["account_type_id" => 5, "name" => "MISCELLANEOUS EXPENSES", "number" => "72370"]);
        Account::create(["account_type_id" => 5, "name" => "DEPRECIATION", "number" => "72380"]);
        Account::create(["account_type_id" => 5, "name" => "OFFICERS HONORARIUM AND ALLOWANCES", "number" => "73150"]);
        Account::create(["account_type_id" => 5, "name" => "LITIGATION EXPENSES", "number" => "73170"]);
        Account::create(["account_type_id" => 5, "name" => "OFFICE SUPPLIES", "number" => "73190"]);
        Account::create(["account_type_id" => 5, "name" => "MEETINGS AND CONFERENCES", "number" => "73200"]);
        Account::create(["account_type_id" => 5, "name" => "TRAININGS/SEMINARS", "number" => "73210"]);
        Account::create(["account_type_id" => 5, "name" => "COLLECTION EXPENSE", "number" => "73320"]);
        Account::create(["account_type_id" => 5, "name" => "GENERAL SUPPORT SERVICES", "number" => "73330"]);
        Account::create(["account_type_id" => 5, "name" => "BANK CHARGES", "number" => "73400"]);
        Account::create(["account_type_id" => 5, "name" => "GENERAL ASSEMBLY EXPENSES", "number" => "73410"]);
        Account::create(["account_type_id" => 5, "name" => "COOPERATIVE CELEBRATION EXPENSE", "number" => "73420"]);
        Account::create(["account_type_id" => 5, "name" => "MEMBERS BENEFIT EXPENSE", "number" => "73430"]);
        Account::create(["account_type_id" => 5, "name" => "AFFILIATION FEE", "number" => "73440"]);
        Account::create(["account_type_id" => 5, "name" => "SOCIAL & COMMUNITY SERVICE EXPENSE", "number" => "73450"]);
    }
}
