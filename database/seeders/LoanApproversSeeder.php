<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\Models\Permission;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class LoanApproversSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $permission = Permission::create([
            'name' => 'approve loan applications'
        ]);

        // Credit Committee Secretary
        $role = Role::create([
            'name' => 'crecom-secretary'
        ]);
        $user = User::create([
            'name' => 'CATHERINE A. LEGASPI',
            'email' => 'crecomsecretary@gmail.com',
            'password' => Hash::make('password')
        ]);
        $role->givePermissionTo($permission);
        $user->assignRole($role);

        // Credit Committee Vice Chairperson
        $role = Role::create([
            'name' => 'crecom-vicechairperson'
        ]);
        $user = User::create([
            'name' => 'JUVEN LACONSE',
            'email' => 'crecomvicechairperson@gmail.com',
            'password' => Hash::make('password')
        ]);
        $role->givePermissionTo($permission);
        $user->assignRole($role);

        // Credit Committee Chairperson
        $role = Role::create([
            'name' => 'crecom-chairperson'
        ]);
        $user = User::create([
            'name' => 'JACQUILINE B. CANDIDO',
            'email' => 'crecomchairperson@gmail.com',
            'password' => Hash::make('password')
        ]);
        $role->givePermissionTo($permission);
        $user->assignRole($role);

        // BOD Chairperson
        $role = Role::create([
            'name' => 'bod-chairperson'
        ]);
        $user = User::create([
            'name' => 'ROLANDO F. HECHANOVA, RPAE, Ph.D.',
            'email' => 'bodchairperson@gmail.com',
            'password' => Hash::make('password')
        ]);
        $role->givePermissionTo($permission);
        $user->assignRole($role);
    }
}
