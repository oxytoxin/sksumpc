<?php

namespace Database\Seeders;

use App\Models\TransactionDateHistory;
use Illuminate\Database\Seeder;

class DatabaseUpdatesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        TransactionDateHistory::create([
            'date' => '12/31/2024',
            'is_current' => true
        ]);
    }
}
