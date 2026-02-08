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
        Schema::table('time_deposits', function (Blueprint $table) {
            $table->timestamp('auto_rolled_over_at')->nullable()->after('withdrawal_date');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('time_deposits', function (Blueprint $table) {
            $table->dropColumn('auto_rolled_over_at');
        });
    }
};
