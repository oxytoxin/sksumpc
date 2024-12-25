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
        Schema::table('savings', function (Blueprint $table) {
            $table->timestamp('transaction_datetime')->virtualAs('TIMESTAMP(transaction_date, CAST(created_at as TIME))')->after('transaction_date');
        });
        Schema::table('imprests', function (Blueprint $table) {
            $table->timestamp('transaction_datetime')->virtualAs('TIMESTAMP(transaction_date, CAST(created_at as TIME))')->after('transaction_date');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('savings', function (Blueprint $table) {
            $table->dropColumn('transaction_datetime');
        });
        Schema::table('imprests', function (Blueprint $table) {
            $table->dropColumn('transaction_datetime');
        });
    }
};
