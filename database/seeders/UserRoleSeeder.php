<?php

namespace Database\Seeders;

use App\Models\SignatureSet;
use App\Models\User;
use Illuminate\Database\Seeder;
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
            'password' => 'password',
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
            'password' => 'CATaro2024',
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
            'password' => '1234567',
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
            'password' => '072820',
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
            'password' => 'EDA070784',
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
            'password' => 'gwapaako',
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
            'password' => 'password',
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
            'password' => 'password',
        ]);
        $user->assignRole($role);

        $bookkeeper = User::whereRelation('roles', 'name', 'book-keeper')->first();
        $treasurer = User::whereRelation('roles', 'name', 'treasurer')->first();
        $manager = User::whereRelation('roles', 'name', 'manager')->first();
        $cashier = User::whereRelation('roles', 'name', 'cashier')->first();
        $loan_officer = User::whereRelation('roles', 'name', 'loan-staff')->first();
        $cbu_staff = User::whereRelation('roles', 'name', 'cbu-staff')->first();
        $clerk = User::whereRelation('roles', 'name', 'clerk')->first();
        $crecom_chairperson = User::whereRelation('roles', 'name', 'crecom-chairperson')->first();
        $set = SignatureSet::create([
            'name' => 'Cashier Reports',
        ]);
        $set->signatories()->create([
            'action' => 'Prepared by:',
            'user_id' => $cashier->id,
            'designation' => 'Teller/Cashier',
        ]);
        $set->signatories()->create([
            'action' => 'Checked by:',
            'user_id' => $bookkeeper->id,
            'designation' => 'Posting Clerk',
        ]);
        $set->signatories()->create([
            'action' => 'Received by:',
            'user_id' => $treasurer->id,
            'designation' => 'Treasurer',
        ]);
        $set->signatories()->create([
            'action' => 'Noted:',
            'user_id' => $manager->id,
            'designation' => 'Manager',
        ]);

        $set = SignatureSet::create([
            'name' => 'Loan Disclosure Sheet Reports',
        ]);
        $set->signatories()->create([
            'action' => 'Prepared by:',
            'user_id' => $loan_officer->id,
            'designation' => 'Loan Officer',
        ]);
        $set->signatories()->create([
            'action' => 'Checked by:',
            'user_id' => $bookkeeper->id,
            'designation' => 'Posting Clerk',
        ]);
        $set->signatories()->create([
            'action' => 'Received by:',
            'user_id' => $treasurer->id,
            'designation' => 'Treasurer',
        ]);
        $set->signatories()->create([
            'action' => 'Noted:',
            'user_id' => $manager->id,
            'designation' => 'Manager',
        ]);

        $set = SignatureSet::create([
            'name' => 'Loan Officer Reports',
        ]);
        $set->signatories()->create([
            'action' => 'Prepared by:',
            'user_id' => $loan_officer->id,
            'designation' => 'Loan Officer',
        ]);
        $set->signatories()->create([
            'action' => 'Noted:',
            'user_id' => $manager->id,
            'designation' => 'Manager',
        ]);
        $set = SignatureSet::create([
            'name' => 'CBU Reports',
        ]);
        $set->signatories()->create([
            'action' => 'Prepared by:',
            'user_id' => $clerk->id,
            'designation' => 'Clerk',
        ]);
        $set->signatories()->create([
            'action' => 'Noted:',
            'user_id' => $manager->id,
            'designation' => 'Manager',
        ]);

        // Credit Committee Investigator
        $role = Role::create([
            'name' => 'credit-investigator',
        ]);
        $permission = Permission::create([
            'name' => 'investigate cibi',
        ]);
        $cibi_investigator = User::firstWhere('name', 'Jayson Landayao');
        $role->givePermissionTo($permission);
        $cibi_investigator->assignRole($role);

        $set = SignatureSet::create([
            'name' => 'CIBI Reports',
        ]);
        $set->signatories()->create([
            'action' => 'Prepared by:',
            'user_id' => $cibi_investigator->id,
            'designation' => 'Credit Investigator',
        ]);
        $set->signatories()->create([
            'action' => 'Checked by:',
            'user_id' => $crecom_chairperson->id,
            'designation' => 'Credit Committee Chairperson',
        ]);

        $set = SignatureSet::create([
            'name' => 'SL Reports',
        ]);
        $set->signatories()->create([
            'action' => 'Prepared by:',
            'user_id' => $cashier->id,
            'designation' => 'Teller/Cashier',
        ]);
        $set->signatories()->create([
            'action' => 'Noted:',
            'user_id' => $manager->id,
            'designation' => 'Manager',
        ]);
        $set = SignatureSet::create([
            'name' => 'Loan Amortization Reports',
        ]);
        $set->signatories()->create([
            'action' => 'Prepared by:',
            'user_id' => $cashier->id,
            'designation' => 'Teller/Cashier',
        ]);
        $set->signatories()->create([
            'action' => 'Noted:',
            'user_id' => $manager->id,
            'designation' => 'Manager',
        ]);
    }
}
