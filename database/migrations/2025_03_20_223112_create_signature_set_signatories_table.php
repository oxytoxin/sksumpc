<?php

use App\Models\SignatureSet;
use App\Models\User;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('signature_set_signatories', function (Blueprint $table) {
            $table->id();
            $table->foreignId('signature_set_id')->constrained();
            $table->string('action');
            $table->foreignId('user_id')->constrained();
            $table->string('designation');
            $table->timestamps();
        });


        $bookkeeper = User::whereRelation('roles', 'name', 'book-keeper')->first();
        $treasurer = User::whereRelation('roles', 'name', 'treasurer')->first();
        $manager = User::whereRelation('roles', 'name', 'manager')->first();
        $cashier = User::whereRelation('roles', 'name', 'cashier')->first();
        $loan_officer = User::whereRelation('roles', 'name', 'loan-staff')->first();
        $cbu_staff = User::whereRelation('roles', 'name', 'cbu-staff')->first();
        $clerk = User::whereRelation('roles', 'name', 'clerk')->first();
        $crecom_chairperson = User::whereRelation('roles', 'name', 'crecom-chairperson')->first();
        $set = SignatureSet::create([
            'name' => 'Cashier Reports'
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
            'name' => 'Loan Disclosure Sheet Reports'
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
            'name' => 'Loan Officer Reports'
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
            'name' => 'CBU Reports'
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
            'name' => 'CIBI Reports'
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
            'name' => 'SL Reports'
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
            'name' => 'Loan Amortization Reports'
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

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('signature_set_signatories');
    }
};
