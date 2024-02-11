<?php

namespace Database\Seeders;

use App\Models\AccountType;
use Illuminate\Database\Seeder;

class AccountTypeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        AccountType::create([
            'name' => 'ASSETS',
            'debit_operator' => 1,
            'credit_operator' => -1,
        ]);
        AccountType::create([
            'name' => 'LIABILITIES',
            'debit_operator' => -1,
            'credit_operator' => 1,
        ]);
        AccountType::create([
            'name' => 'REVENUE',
            'debit_operator' => -1,
            'credit_operator' => 1,
        ]);
        AccountType::create([
            'name' => 'EQUITY',
            'debit_operator' => -1,
            'credit_operator' => 1,
        ]);
        AccountType::create([
            'name' => 'EXPENSES',
            'debit_operator' => 1,
            'credit_operator' => -1,
        ]);
    }
}
