<?php

use App\Models\SignatureSet;
use App\Models\SignatureSetSignatory;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        $signatureSet = SignatureSet::firstOrCreate([
            'name' => 'Coborrower Undertaking',
        ]);

        $treasurerId = DB::table('users')
            ->join('model_has_roles', 'users.id', '=', 'model_has_roles.model_id')
            ->join('roles', 'model_has_roles.role_id', '=', 'roles.id')
            ->where('roles.name', 'treasurer')
            ->where('model_has_roles.model_type', 'App\\Models\\User')
            ->value('users.id');

        if ($treasurerId) {
            SignatureSetSignatory::firstOrCreate([
                'signature_set_id' => $signatureSet->id,
                'user_id' => $treasurerId,
                'action' => 'Signed in the presence of:',
                'designation' => 'Treasurer',
            ]);
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        SignatureSet::where('name', 'Coborrower Undertaking')->delete();
    }
};
