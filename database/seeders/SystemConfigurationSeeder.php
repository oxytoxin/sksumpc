<?php

namespace Database\Seeders;

use App\Models\SystemConfiguration;
use Illuminate\Database\Seeder;

class SystemConfigurationSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        SystemConfiguration::create([
            'name' => 'Savings',
            'content' => [
                'Minimum Amount for Interest' => 1000,
                'Interest Rate' => 0.01,
                'Transfer Code' => '#TRANSFERFROMSAVINGS',
            ],
        ]);
        SystemConfiguration::create([
            'name' => 'Imprest',
            'content' => [
                'Minimum Amount for Interest' => 1000,
                'Interest Rate' => 0.02,
                'Transfer Code' => '#TRANSFERFROMIMPRESTS',
            ],
        ]);
    }
}
