<?php

namespace Database\Seeders;

use App\Models\PaymentType;
use Illuminate\Database\Seeder;

class PaymentTypesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        PaymentType::create([
            'name' => 'OR',
        ]);
        PaymentType::create([
            'name' => 'JEV',
        ]);
        PaymentType::create([
            'name' => 'CV',
        ]);
        PaymentType::create([
            'name' => 'CASH',
        ]);
        PaymentType::create([
            'name' => 'CHECK',
        ]);
        PaymentType::create([
            'name' => 'ADA/ADV',
        ]);
    }
}
