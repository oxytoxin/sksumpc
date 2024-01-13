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
        Schema::create('loan_types', function (Blueprint $table) {
            $table->id();
            $table->string('code');
            $table->string('name');
            $table->decimal('minimum_cbu', 18, 4)->default(0);
            $table->decimal('max_amount', 18, 4)->default();
            $table->decimal('interest_rate', 7, 4)->default(0);
            $table->decimal('surcharge_rate', 7, 4)->default(0);
            $table->decimal('service_fee', 7, 4)->default(0);
            $table->decimal('cbu_common', 7, 4)->default(0);
            $table->decimal('imprest', 7, 4)->default(0);
            $table->decimal('insurance', 7, 4)->default(0);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('loan_types');
    }
};
