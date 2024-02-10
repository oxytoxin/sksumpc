<?php

namespace Database\Seeders;

use App\Models\TransactionType;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class TransactionTypesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        TransactionType::create([
            'name' => 'CRJ'
        ]);
        TransactionType::create([
            'name' => 'CDJ'
        ]);
        TransactionType::create([
            'name' => 'JEV'
        ]);
    }
}
