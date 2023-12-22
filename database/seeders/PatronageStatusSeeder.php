<?php

namespace Database\Seeders;

use App\Models\PatronageStatus;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class PatronageStatusSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        PatronageStatus::create([
            'name' => 'Good Standing'
        ]);
        PatronageStatus::create([
            'name' => 'Bad Standing'
        ]);
        PatronageStatus::create([
            'name' => 'Court Order'
        ]);
        PatronageStatus::create([
            'name' => 'Board Hearing'
        ]);
    }
}
