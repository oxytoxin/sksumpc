<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Artisan;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $this->call(SystemConfigurationSeeder::class);
        $this->call(UserRoleSeeder::class);
        $this->call(DisapprovalReasonSeeder::class);
        $this->call(LoanApproversSeeder::class);
        $this->call(LoanTypeSeeder::class);
        $this->call(PaymentTypesSeeder::class);
        $this->call(CashCollectiblesSeeder::class);
        $this->call(PatronageStatusSeeder::class);
        $this->call(GenderSeeder::class);
        $this->call(TrialBalanceEntrySeeder::class);
        $this->command->info("Seeding members...\n");
        Artisan::call('app:seed-members');
        $this->command->info("Seeded members...\n");

        if (App::environment('local')) {
            $this->call(SeedInitialTestData::class);
        }
    }
}
