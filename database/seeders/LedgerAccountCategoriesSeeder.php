<?php

namespace Database\Seeders;

use App\Models\LedgerAccountCategory;
use Illuminate\Database\Seeder;

class LedgerAccountCategoriesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        LedgerAccountCategory::create([
            'name' => 'assets',
        ]);
        LedgerAccountCategory::create([
            'name' => 'liabilities',
        ]);
        LedgerAccountCategory::create([
            'name' => 'equity',
        ]);
        LedgerAccountCategory::create([
            'name' => 'revenue',
        ]);
        LedgerAccountCategory::create([
            'name' => 'expenses',
        ]);
    }
}
