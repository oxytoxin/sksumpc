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
        Schema::create('cash_collectible_payments', function (Blueprint $table) {
            $table->id();
            $table->foreignId('cash_collectible_id')->constrained();
            $table->foreignId('member_id')->nullable()->constrained();
            $table->string('payee');
            $table->foreignId('payment_type_id')->constrained();
            $table->string('reference_number');
            $table->decimal('amount', 18, 4);
            $table->date('transaction_date');
            $table->foreignId('cashier_id')->nullable()->constrained('users', 'id')->nullOnDelete();

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('cash_collectible_payments');
    }
};
