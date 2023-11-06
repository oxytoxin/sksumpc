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
            'name' => 'RICE&GROCERIES'
        ]);
        CashCollectible::create([
            'name' => 'DORMITORY'
        ]);
        CashCollectible::create([
            'name' => 'PLANE TICKET'
        ]);
        CashCollectible::create([
            'name' => 'CLOTH'
        ]);
        CashCollectible::create([
            'name' => 'INSURANCE'
        ]);
        CashCollectible::create([
            'name' => 'MISCELLANEOUS'
        ]);
        CashCollectible::create([
            'name' => 'CONSIGNMENT'
        ]);
        CashCollectible::create([
            'name' => 'BOOKS'
        ]);
        CashCollectible::create([
            'name' => 'LAND RENTAL'
        ]);
    }
}
