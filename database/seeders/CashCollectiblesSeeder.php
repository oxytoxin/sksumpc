<?php

namespace Database\Seeders;

use App\Models\CashCollectible;
use App\Models\CashCollectibleCategory;
use Illuminate\Database\Seeder;

class CashCollectiblesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $ccg = CashCollectibleCategory::create([
            'name' => 'RICE AND GROCERIES',
        ]);
        CashCollectible::create([
            'cash_collectible_category_id' => $ccg->id,
            'name' => 'RICE',
        ]);
        CashCollectible::create([
            'cash_collectible_category_id' => $ccg->id,
            'name' => 'GROCERIES',
        ]);

        $ccg = CashCollectibleCategory::create([
            'name' => 'LADIES DORMITORY',
        ]);

        CashCollectible::create([
            'cash_collectible_category_id' => $ccg->id,
            'name' => 'RESERVATION',
        ]);
        CashCollectible::create([
            'cash_collectible_category_id' => $ccg->id,
            'name' => 'RENTALS',
        ]);
        CashCollectible::create([
            'cash_collectible_category_id' => $ccg->id,
            'name' => 'ELECTRICITY',
        ]);

        $ccg = CashCollectibleCategory::create([
            'name' => 'OTHERS',
        ]);
        CashCollectible::create([
            'cash_collectible_category_id' => $ccg->id,
            'name' => 'LOVE GIFT',
        ]);
        CashCollectible::create([
            'cash_collectible_category_id' => $ccg->id,
            'name' => 'A/P',
        ]);
        CashCollectible::create([
            'cash_collectible_category_id' => $ccg->id,
            'name' => 'PLANE TICKET',
        ]);
        CashCollectible::create([
            'cash_collectible_category_id' => $ccg->id,
            'name' => 'CLOTH',
        ]);
        CashCollectible::create([
            'cash_collectible_category_id' => $ccg->id,
            'name' => 'INSURANCE',
        ]);
        CashCollectible::create([
            'cash_collectible_category_id' => $ccg->id,
            'name' => 'MISCELLANEOUS',
        ]);
        CashCollectible::create([
            'cash_collectible_category_id' => $ccg->id,
            'name' => 'CONSIGNMENT',
        ]);
        CashCollectible::create([
            'cash_collectible_category_id' => $ccg->id,
            'name' => 'BOOKS',
        ]);
        CashCollectible::create([
            'cash_collectible_category_id' => $ccg->id,
            'name' => 'LAND RENTAL',
        ]);
    }
}
