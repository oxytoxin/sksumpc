<?php

namespace Database\Seeders;

use App\Models\Account;
use Illuminate\Database\Seeder;

class AssetAccountsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Account::create(['account_type_id' => 1, 'name' => 'CASH AND CASH EQUIVALENTS', 'number' => '11100', 'children' => [
            ['account_type_id' => 1, 'name' => 'CASH ON HAND', 'number' => '11110', 'tag' => 'cash_on_hand'],
            ['account_type_id' => 1, 'name' => 'CASH IN BANK', 'number' => '11130', 'tag' => 'cash_in_bank', 'show_sum' => false, 'children' => [
                ['account_type_id' => 1, 'name' => 'DBP (GENERAL FUND)', 'number' => '11130-0001', 'tag' => 'cash_in_bank_dbp_gf'],
                ['account_type_id' => 1, 'name' => 'DBP (MSO FUND)', 'number' => '11130-0002', 'tag' => 'cash_in_bank_dbp_mso'],
                ['account_type_id' => 1, 'name' => 'LBP', 'number' => '11130-0003', 'tag' => 'cash_in_bank_lbp'],
            ]],
            ['account_type_id' => 1, 'name' => 'CASH IN COOPERATIVE FEDERATION', 'number' => '11140'],
            ['account_type_id' => 1, 'name' => 'PETTY CASH FUND', 'number' => '11150', 'tag' => 'petty_cash_fund'],
            ['account_type_id' => 1, 'name' => 'REVOLVING FUND', 'number' => '11160', 'tag' => 'revolving_fund'],
            ['account_type_id' => 1, 'name' => 'ADVANCES TO OFFICERS, EMPLOYEES AND MEMBERS', 'number' => '11360'],
            ['account_type_id' => 1, 'name' => 'CHANGE FUND', 'number' => '11170'],
            ['account_type_id' => 1, 'name' => 'ATM FUND', 'number' => '11180'],
            ['account_type_id' => 1, 'name' => 'E-WALLET FUND', 'number' => '11190'],
        ]]);
        Account::create(['account_type_id' => 1, 'name' => 'LOAN RECEIVABLES', 'number' => '11210', 'tag' => 'loan_receivables', 'sum_description' => 'NET', 'children' => [
            ['account_type_id' => 1, 'name' => 'ALLOWANCE FOR PROBABLE LOSSES-LOANS', 'number' => '11242', 'tag' => 'probable_loss', 'sort' => 1000],
        ]]);
        Account::create(['account_type_id' => 1, 'name' => 'ACCOUNT RECEIVABLES', 'number' => '11250', 'tag' => 'account_receivables', 'sum_description' => 'NET', 'children' => [
            ['account_type_id' => 1, 'name' => 'ALLOWANCE FOR PROBABLE LOSSES-RECEIVABLES', 'number' => '11281', 'tag' => 'probable_loss', 'sort' => 1000],
        ]]);
        Account::create(['account_type_id' => 1, 'name' => 'MERCHANDISE INVENTORY', 'number' => '11510', 'tag' => 'merchandise_inventory']);
        Account::create(['account_type_id' => 1, 'name' => 'UNUSED SUPPLIES', 'number' => '12150']);
        Account::create(['account_type_id' => 1, 'name' => 'PREPAID EXPENSES', 'number' => '12170']);
        Account::create(['account_type_id' => 1, 'name' => 'OTHER CURRENT ASSETS', 'number' => '12200', 'children' => [
            ['account_type_id' => 1, 'name' => 'DEPOSIT TO SUPPLIERS', 'number' => '12140'],
        ]]);
        Account::create(['account_type_id' => 1, 'name' => 'NON CURRENT ASSETS', 'number' => '12300', 'children' => [
            ['account_type_id' => 1, 'name' => 'INVESTMENT PROPERTY-LAND', 'number' => '13510'],
            ['account_type_id' => 1, 'name' => 'INVESTMENT PROPERTY-BUILDING', 'number' => '13520'],
            ['account_type_id' => 1, 'name' => 'ACCU. DEPRECIATION-INV.PROP BUILDING', 'number' => '13521'],
            ['account_type_id' => 1, 'name' => 'COMPUTERIZATION COST', 'number' => '18100'],
            ['account_type_id' => 1, 'name' => 'ACCU. DEPRECIATION-COMPUTERIZATION COST', 'number' => '18101'],
            ['account_type_id' => 1, 'name' => 'OTHER INVESTMENT-CLIMBS', 'number' => '13530'],
            ['account_type_id' => 1, 'name' => 'OTHER INVESTMENT-TICKETING OFFICE', 'number' => '13540'],
            ['account_type_id' => 1, 'name' => 'OTHER INVESTMENT-CASH BOND PAL', 'number' => '13550'],
        ]]);

        Account::create(['account_type_id' => 1, 'name' => 'PROPERTY AND EQUIPMENT', 'number' => '14000', 'sum_description' => 'NET', 'children' => [
            ['account_type_id' => 1, 'name' => 'LAND', 'number' => '14100'],
            ['account_type_id' => 1, 'name' => 'LAND IMPROVEMENTS', 'number' => '14110'],
            ['account_type_id' => 1, 'name' => 'ACCU. DEPRECIATION-LAND IMPROVEMENTS', 'number' => '14111'],
            ['account_type_id' => 1, 'name' => 'DORMITORY', 'number' => '14120'],
            ['account_type_id' => 1, 'name' => 'ACCU. DEPRECIATION-DORMITORY', 'number' => '14131'],
            ['account_type_id' => 1, 'name' => 'FURNITURE, FIXTURES & EQUIPMENT(FFE)', 'number' => '14180'],
            ['account_type_id' => 1, 'name' => 'ACCU. DEPRECIATION-FFE', 'number' => '14181'],
            ['account_type_id' => 1, 'name' => 'OFFICE BUILDING', 'number' => '14130'],
            ['account_type_id' => 1, 'name' => 'ACCU. DEPRECIATION-BUILDING', 'number' => '14121'],
            ['account_type_id' => 1, 'name' => 'OFFICE BUILDING EXTENSION', 'number' => '14290'],
            ['account_type_id' => 1, 'name' => 'ACCU. DEPRECIATION-BUILDING EXTENSION', 'number' => '14291'],
        ]]);

        Account::create(['account_type_id' => 1, 'name' => 'OTHER FUNDS AND DEPOSITS', 'number' => '18200', 'children' => [
            ['account_type_id' => 1, 'name' => 'RESERVE FUND', 'number' => '18200-001'],
            ['account_type_id' => 1, 'name' => 'CETF', 'number' => '18200-002'],
            ['account_type_id' => 1, 'name' => 'OPTIONAL FUND', 'number' => '18200-003'],
            ['account_type_id' => 1, 'name' => 'COMMUNITY DEVELOPMENT FUND', 'number' => '18200-004'],
            ['account_type_id' => 1, 'name' => 'DBP TIME DEPOSIT', 'number' => '18200-005'],
            ['account_type_id' => 1, 'name' => 'SKSU MPC STATUTORY FUNDS', 'number' => '18200-006'],
            ['account_type_id' => 1, 'name' => 'TREASURY BILLS', 'number' => '18200-007'],
        ]]);
        Account::create(['account_type_id' => 1, 'name' => 'MISCELLANEOUS ASSETS', 'number' => '17900']);
    }
}
