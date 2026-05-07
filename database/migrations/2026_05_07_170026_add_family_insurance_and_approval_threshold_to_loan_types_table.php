<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('loan_types', function (Blueprint $table) {
            $table->decimal('family_insurance', 18, 4)->default(190)->after('insurance');
            $table->decimal('approval_threshold', 18, 4)->default(50000)->after('family_insurance');
        });
    }

    public function down(): void
    {
        Schema::table('loan_types', function (Blueprint $table) {
            $table->dropColumn(['family_insurance', 'approval_threshold']);
        });
    }
};
