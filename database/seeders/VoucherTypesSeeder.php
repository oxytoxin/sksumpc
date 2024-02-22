<?php

namespace Database\Seeders;

use App\Models\VoucherType;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class VoucherTypesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        VoucherType::create([
            'name' => 'LOANS',
        ]);
        VoucherType::create([
            'name' => 'MSO',
        ]);
        VoucherType::create([
            'name' => 'RICE',
        ]);
        VoucherType::create([
            'name' => 'LABORATORY',
        ]);
        VoucherType::create([
            'name' => 'DORM',
        ]);
        VoucherType::create([
            'name' => 'OTHERS',
        ]);
    }
}
