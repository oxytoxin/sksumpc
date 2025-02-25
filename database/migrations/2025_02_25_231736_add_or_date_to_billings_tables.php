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
        Schema::table('loan_billings', function (Blueprint $table) {
            $table->date('or_date')->nullable()->after('or_number');
        });
        Schema::table('capital_subscription_billings', function (Blueprint $table) {
            $table->date('or_date')->nullable()->after('or_number');
        });
        Schema::table('mso_billings', function (Blueprint $table) {
            $table->date('or_date')->nullable()->after('or_number');
        });
        Schema::table('cash_collectible_billings', function (Blueprint $table) {
            $table->date('or_date')->nullable()->after('or_number');
        });
    }
};
