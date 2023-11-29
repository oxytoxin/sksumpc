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
        $this->call(UserRoleSeeder::class);
        $this->call(DisapprovalReasonSeeder::class);
        $this->call(LoanApproversSeeder::class);
        $this->call(PaymentTypesSeeder::class);
        $this->call(CashCollectiblesSeeder::class);
        Artisan::call('app:seed-members');
    }
}
