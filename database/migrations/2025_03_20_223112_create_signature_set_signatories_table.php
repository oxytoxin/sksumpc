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
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('signature_set_signatories');
    }
};
