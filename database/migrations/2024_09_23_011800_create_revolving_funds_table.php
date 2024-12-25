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
        Schema::create('revolving_funds', function (Blueprint $table) {
            $table->id();
            $table->decimal('deposit', 18, 4)->nullable();
            $table->decimal('withdrawal', 18, 4)->nullable();
            $table->string('reference_number');
            $table->date('transaction_date')->default(DB::raw('(CURRENT_DATE)'));
            $table->foreignId('cashier_id')->constrained('users');
            $table->nullableMorphs('withdrawable');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('revolving_funds');
    }
};
