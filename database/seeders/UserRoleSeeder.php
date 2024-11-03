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

        $permission = Permission::create([
            'name' => 'manage all',
        ]);
        $role->givePermissionTo($permission);

        $user->assignRole($role);

        // Cashier
        $role = Role::create([
            'name' => 'cashier',
        ]);
        $user = User::create([
            'name' => 'CRISTY A. MANTOS',
            'email' => 'carochi2024@gmail.com',
            'password' => Hash::make('CATaro2024'),
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
            'email' => 'MPCCBU@gmail.com',
            'password' => Hash::make('1234567'),
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
            'email' => 'CJLsexy@gmail.com@gmail.com',
            'password' => Hash::make('072820'),
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
            'password' => Hash::make('EDA070784'),
        ]);
        $role->givePermissionTo($permission);
        $user->assignRole($role);

        // Bookkeeper
        $role = Role::create([
            'name' => 'book-keeper',
        ]);
        $user = User::create([
            'name' => 'JOANA MONA R. PRIMACIO ',
            'email' => 'sksumpcbookkeeper@gmail.com',
            'password' => Hash::make('gwapaako'),
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

        $role = Role::create([
            'name' => 'member',
        ]);
        $permission = Permission::create([
            'name' => 'view own member profile',
        ]);
        $role->givePermissionTo($permission);

        $permission = Permission::create([
            'name' => 'handle clerical tasks',
        ]);
        $role = Role::create([
            'name' => 'clerk',
        ]);
        $role->givePermissionTo($permission);
        $user = User::create([
            'name' => 'SKSU BILLING CLERK',
            'email' => 'sksumpcclerk@gmail.com',
            'password' => Hash::make('password'),
        ]);
        $user->assignRole($role);

        $role = Role::where('name', 'clerk')->first();
        $user = User::create([
            'name' => 'SKSU BILLING CLERK',
            'email' => 'sksumpcclerk@gmail.com',
            'password' => Hash::make('password'),
        ]);
        $user->assignRole($role);
    }
}
