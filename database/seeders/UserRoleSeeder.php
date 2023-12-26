<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class UserRoleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Manager
        $role = Role::create([
            'name' => 'manager',
        ]);
        $user = User::create([
            'name' => 'FLORA C. DAMANDAMAN',
            'email' => 'sksumpcadmin@gmail.com',
            'password' => Hash::make('password'),
        ]);
        $user->assignRole($role);

        // Cashier
        $role = Role::create([
            'name' => 'cashier',
        ]);
        $user = User::create([
            'name' => 'CRISTY A. MANTOS',
            'email' => 'sksumpccashier@gmail.com',
            'password' => Hash::make('password'),
        ]);
        $permission = Permission::create([
            'name' => 'manage payments',
        ]);
        $role->givePermissionTo($permission);
        $user->assignRole($role);

        // CBU Staff
        $role = Role::create([
            'name' => 'cbu-staff',
        ]);
        $user = User::create([
            'name' => 'ADRIAN VOLTAIRE POLO',
            'email' => 'sksumpccbu@gmail.com',
            'password' => Hash::make('password'),
        ]);
        $permission = Permission::create([
            'name' => 'manage members',
        ]);
        $role->givePermissionTo($permission);
        $permission = Permission::create([
            'name' => 'manage cbu',
        ]);
        $role->givePermissionTo($permission);
        $user->assignRole($role);

        // MSO Staff
        $role = Role::create([
            'name' => 'mso-staff',
        ]);
        $user = User::create([
            'name' => 'SKSUMPC MSO Staff',
            'email' => 'sksumpcmso@gmail.com',
            'password' => Hash::make('password'),
        ]);
        $permission = Permission::create([
            'name' => 'manage mso',
        ]);
        $role->givePermissionTo($permission);
        $user->assignRole($role);

        // Loan Staff
        $permission = Permission::create([
            'name' => 'manage loans',
        ]);
        $role = Role::create([
            'name' => 'loan-staff',
        ]);
        $user = User::create([
            'name' => 'SKSUMPC Loan Staff',
            'email' => 'sksumpcloan@gmail.com',
            'password' => Hash::make('password'),
        ]);
        $role->givePermissionTo($permission);
        $user->assignRole($role);

        // Bookkeeper
        $role = Role::create([
            'name' => 'book-keeper',
        ]);
        $user = User::create([
            'name' => 'ADRIAN VOLTAIRE POLO',
            'email' => 'sksumpcbookkeeper@gmail.com',
            'password' => Hash::make('password'),
        ]);
        $permission = Permission::create([
            'name' => 'manage bookkeeping',
        ]);
        $role->givePermissionTo($permission);
        $user->assignRole($role);

        // Treasurer
        $role = Role::create([
            'name' => 'treasurer',
        ]);
        $user = User::create([
            'name' => 'DESIREE G. LEGASPI',
            'email' => 'sksumpctreasurer@gmail.com',
            'password' => Hash::make('password'),
        ]);
        $permission = Permission::create([
            'name' => 'manage treasury',
        ]);
        $role->givePermissionTo($permission);
        $user->assignRole($role);
    }
}
