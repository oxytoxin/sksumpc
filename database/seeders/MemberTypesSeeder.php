<?php

namespace Database\Seeders;

use App\Models\MemberType;
use Illuminate\Database\Seeder;

class MemberTypesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        MemberType::create([
            'name' => 'REGULAR',
            'minimum_initial_payment' => 6500,
            'default_amount_subscribed' => 25000,
            'default_number_of_shares' => 50,
            'par_value' => 500,
            'surcharge_rate' => 0.01,
            'initial_number_of_terms' => 12,
            'additional_number_of_terms' => 36,
        ]);
        MemberType::create([
            'name' => 'REGULAR-JO',
            'minimum_initial_payment' => 6500,
            'default_amount_subscribed' => 25000,
            'default_number_of_shares' => 50,
            'par_value' => 500,
            'surcharge_rate' => 0.01,
            'initial_number_of_terms' => 12,
            'additional_number_of_terms' => 36,
        ]);
        MemberType::create([
            'name' => 'ASSOCIATE',
            'minimum_initial_payment' => 2500,
            'default_amount_subscribed' => 10000,
            'default_number_of_shares' => 20,
            'par_value' => 500,
            'surcharge_rate' => 0.01,
            'initial_number_of_terms' => 12,
            'additional_number_of_terms' => 36,
        ]);
        MemberType::create([
            'name' => 'LABORATORY',
            'minimum_initial_payment' => 2500,
            'default_amount_subscribed' => 10000,
            'default_number_of_shares' => 20,
            'par_value' => 500,
            'surcharge_rate' => 0.01,
            'initial_number_of_terms' => 12,
            'additional_number_of_terms' => 36,
        ]);
        MemberType::create([
            'name' => 'RETIREE',
            'minimum_initial_payment' => 2500,
            'default_amount_subscribed' => 10000,
            'default_number_of_shares' => 20,
            'par_value' => 500,
            'surcharge_rate' => 0.01,
            'initial_number_of_terms' => 12,
            'additional_number_of_terms' => 36,
        ]);
        MemberType::create([
            'name' => 'REGULAR-NOT CONNECTED',
            'minimum_initial_payment' => 2500,
            'default_amount_subscribed' => 10000,
            'default_number_of_shares' => 20,
            'par_value' => 500,
            'surcharge_rate' => 0.01,
            'initial_number_of_terms' => 12,
            'additional_number_of_terms' => 36,
        ]);
        MemberType::create([
            'name' => 'ORGANIZATION',
            'minimum_initial_payment' => 6500,
            'default_amount_subscribed' => 25000,
            'default_number_of_shares' => 50,
            'par_value' => 500,
            'surcharge_rate' => 0.01,
            'initial_number_of_terms' => 12,
            'additional_number_of_terms' => 36,
        ]);
    }
}
