<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Artisan;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $this->call(MemberTypesSeeder::class);
        $this->call(MemberSubtypesSeeder::class);
        $this->call(SystemConfigurationSeeder::class);
        $this->call(UserRoleSeeder::class);
        $this->call(DisapprovalReasonSeeder::class);
        $this->call(LoanApproversSeeder::class);
        $this->call(PaymentTypesSeeder::class);
        $this->call(PatronageStatusSeeder::class);
        $this->call(GenderSeeder::class);
        $this->call(PositionsSeeder::class);

        $this->call(TransactionTypesSeeder::class);
        $this->call(AccountTypeSeeder::class);
        $this->call(AssetAccountsSeeder::class);
        $this->call(LiabilitiesAccountsSeeder::class);
        $this->call(RevenueAccountsSeeder::class);
        $this->call(EquityAccountsSeeder::class);
        $this->call(ExpenseAccountsSeeder::class);

        $this->call(CashCollectiblesSeeder::class);
        $this->call(LoanTypeSeeder::class);
        $this->call(VoucherTypesSeeder::class);

        $this->command->info("Seeding members...\n");
        Artisan::call('app:import-members');
        $this->command->info("Seeding organizations...\n");
        Artisan::call('app:import-organizations');
        $this->command->info("Seeding cbu...\n");
        Artisan::call('app:import-cbu');
        $this->command->info("Seeding mso...\n");
        Artisan::call('app:import-mso');
        $this->command->info("Seeding loans...\n");
        Artisan::call('app:import-loans');

        // $this->call(ImportExistingLoansSeeder::class);
        Artisan::call('app:import-balance-forwarded2024');
        Artisan::call('app:import-member-accounts');
        $this->call(DatabaseUpdatesSeeder::class);
    }
}
