<?php

namespace Database\Seeders;

use App\Models\CashCollectible;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class CashCollectiblesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        CashCollectible::create([
            'name' => 'Rice&Groceries'
        ]);
        CashCollectible::create([
            'name' => 'Dorm'
        ]);
        CashCollectible::create([
            'name' => 'Others'
        ]);
    }
}
