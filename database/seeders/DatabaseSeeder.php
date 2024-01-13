<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;

use Artisan;
use Illuminate\Database\Seeder;

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
        $this->call(LedgerAccountCategoriesSeeder::class);
        $this->call(LedgerAccountsSeeder::class);
        $this->command->info("Seeding members...\n");
        Artisan::call('app:seed-members');
        $this->command->info("Seeded members...\n");
    }
}
