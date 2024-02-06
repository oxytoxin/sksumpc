<?php

namespace Database\Seeders;

use App\Models\Account;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class AssetAccountsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Account::create(["account_type_id" => 1, "name" => "CASH ON HAND", "number" => "11110"]);
        Account::create(["account_type_id" => 1, "name" => "CASH IN BANK", "number" => "11130", "children" => [
            ["account_type_id" => 1, "name" => "DBP (GENERAL FUND)", "number" => "11130-001"],
            ["account_type_id" => 1, "name" => "DBP (MSO FUND)", "number" => "11130-002"],
            ["account_type_id" => 1, "name" => "LBP", "number" => "11130-003"],
        ]]);
        Account::create(["account_type_id" => 1, "name" => "CASH IN COOPERATIVE FEDERATION", "number" => "11140"]);
        Account::create(["account_type_id" => 1, "name" => "PETTY CASH FUND", "number" => "11150"]);
        Account::create(["account_type_id" => 1, "name" => "REVOLVING FUND", "number" => "11160"]);
        Account::create(["account_type_id" => 1, "name" => "CHANGE FUND", "number" => "11170"]);
        Account::create(["account_type_id" => 1, "name" => "ATM FUND", "number" => "11180"]);
        Account::create(["account_type_id" => 1, "name" => "E-WALLET FUND", "number" => "11190"]);
        Account::create(["account_type_id" => 1, "name" => "LOAN RECEIVABLE", "number" => "11210", "tag" => "loan_receivables"]);
        Account::create(["account_type_id" => 1, "name" => "ALLOWANCE FOR PROBABLE LOSSES- LOANS", "number" => "11242"]);
        Account::create(["account_type_id" => 1, "name" => "ACCOUNT RECEIVABLES", "number" => "11250", "tag" => "account_receivables"]);
        Account::create(["account_type_id" => 1, "name" => "ALLOWANCE FOR PROBABLE LOSSES- RECEIVABLES", "number" => "11281"]);
        Account::create(["account_type_id" => 1, "name" => "ADVANCES TO OFFICERS, EMPLOYEES AND MEMBERS", "number" => "11360"]);
        Account::create(["account_type_id" => 1, "name" => "MERCHANDISE INVENTORY", "number" => "11510", "tag" => "merchandise_inventory"]);
        Account::create(["account_type_id" => 1, "name" => "UNUSED SUPPLIES", "number" => "12150"]);
        Account::create(["account_type_id" => 1, "name" => "PREPAID EXPENSES", "number" => "12170"]);
        Account::create(["account_type_id" => 1, "name" => "OTHER CURRENT ASSETS", "number" => "12200"]);
        Account::create(["account_type_id" => 1, "name" => "INVESTMENT PROPERTY-LAND", "number" => "13510"]);
        Account::create(["account_type_id" => 1, "name" => "INVESTMENT PROPERTY- BUILDING", "number" => "13520"]);
        Account::create(["account_type_id" => 1, "name" => "ACCU. DEPRECIATION- INV.PROP BUILDING", "number" => "13521"]);
        Account::create(["account_type_id" => 1, "name" => "LAND", "number" => "14100"]);
        Account::create(["account_type_id" => 1, "name" => "LAND IMPROVEMENTS'", "number" => "14110"]);
        Account::create(["account_type_id" => 1, "name" => "ACCU. DEPRECIATION- LAND IMPROVEMENTS", "number" => "14111"]);
        Account::create(["account_type_id" => 1, "name" => "BUILDING", "number" => "14120"]);
        Account::create(["account_type_id" => 1, "name" => "ACCU. DEPRECIATION- BUILDING", "number" => "14121"]);
        Account::create(["account_type_id" => 1, "name" => "BUILDING IMPROVEMENTS", "number" => "14130"]);
        Account::create(["account_type_id" => 1, "name" => "ACCU. DEPRECIATION-BUILDING IMPROVEMENTS", "number" => "14131"]);
        Account::create(["account_type_id" => 1, "name" => "FURNITURE, FIXTURES & EQUIPMENT(FFE)", "number" => "14180"]);
        Account::create(["account_type_id" => 1, "name" => "ACCU. DEPRECIATION- FFE", "number" => "14181"]);
        Account::create(["account_type_id" => 1, "name" => "OTHER PROPERTY, PLANT AND EQUIPMENT", "number" => "14290"]);
        Account::create(["account_type_id" => 1, "name" => "COMPUTERIZATION COST", "number" => "18100"]);
        Account::create(["account_type_id" => 1, "name" => "OTHER FUNDS AND DEPOSITS", "number" => "18200"]);
        Account::create(["account_type_id" => 1, "name" => "MISCELLANEOUS ASSETS", "number" => "17900"]);
        Account::create(["account_type_id" => 1, "name" => "DEPOSIT TO SUPPLIERS", "number" => "12140"]);
    }
}
