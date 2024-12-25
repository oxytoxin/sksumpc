<?php

namespace Database\Seeders;

use App\Models\MemberSubtype;
use Illuminate\Database\Seeder;

class MemberSubtypesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        MemberSubtype::create([
            'name' => 'PERMANENT',
            'member_type_id' => 1,
        ]);
        MemberSubtype::create([
            'name' => 'JO',
            'member_type_id' => 1,
        ]);
        MemberSubtype::create([
            'name' => 'NOT CONNECTED',
            'member_type_id' => 1,
        ]);
        MemberSubtype::create([
            'name' => 'RETIREE',
            'member_type_id' => 1,
        ]);
        MemberSubtype::create([
            'name' => 'SKSU MPC STAFF',
            'member_type_id' => 1,
        ]);
    }
}
