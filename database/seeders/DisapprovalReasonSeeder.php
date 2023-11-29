<?php

namespace Database\Seeders;

use App\Models\DisapprovalReason;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DisapprovalReasonSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DisapprovalReason::create([
            'name' => 'Others'
        ]);
    }
}
