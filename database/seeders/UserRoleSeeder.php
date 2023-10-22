<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\Models\Permission;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class UserRoleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Manager
        $role = Role::create([
            'name' => 'manager'
        ]);
        $user = User::create([
            'name' => 'SKSUMPC Administrator',
            'email' => 'sksumpcadmin@gmail.com',
            'password' => Hash::make('password')
        ]);
        $user->assignRole($role);

        // Cashier
        $role = Role::create([
            'name' => 'cashier'
        ]);
        $user = User::create([
            'name' => 'SKSUMPC Cashier',
            'email' => 'sksumpccashier@gmail.com',
            'password' => Hash::make('password')
        ]);
        $permission = Permission::create([
            'name' => 'manage payments'
        ]);
        $role->givePermissionTo($permission);
        $user->assignRole($role);

        // CBU Staff
        $role = Role::create([
            'name' => 'cbu-staff'
        ]);
        $user = User::create([
            'name' => 'SKSUMPC CBU Staff',
            'email' => 'sksumpccbu@gmail.com',
            'password' => Hash::make('password')
        ]);
        $permission = Permission::create([
            'name' => 'manage members'
        ]);
        $role->givePermissionTo($permission);
        $permission = Permission::create([
            'name' => 'manage cbu'
        ]);
        $role->givePermissionTo($permission);
        $user->assignRole($role);


        // MSO Staff
        $role = Role::create([
            'name' => 'mso-staff'
        ]);
        $user = User::create([
            'name' => 'SKSUMPC MSO Staff',
            'email' => 'sksumpcmso@gmail.com',
            'password' => Hash::make('password')
        ]);
        $permission = Permission::create([
            'name' => 'manage mso'
        ]);
        $role->givePermissionTo($permission);
        $user->assignRole($role);

        // Loan Staff
        $permission = Permission::create([
            'name' => 'manage loans'
        ]);
        $role = Role::create([
            'name' => 'loan-staff'
        ]);
        $user = User::create([
            'name' => 'SKSUMPC Loan Staff',
            'email' => 'sksumpcloan@gmail.com',
            'password' => Hash::make('password')
        ]);
        $role->givePermissionTo($permission);
        $user->assignRole($role);

        // Bookkeeper
        $role = Role::create([
            'name' => 'book-keeper'
        ]);
        $user = User::create([
            'name' => 'SKSUMPC Bookkeeper',
            'email' => 'sksumpcbookkeeper@gmail.com',
            'password' => Hash::make('password')
        ]);
        $permission = Permission::create([
            'name' => 'manage bookkeeping'
        ]);
        $role->givePermissionTo($permission);
        $user->assignRole($role);
    }
}
