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
        Schema::create('loan_payments', function (Blueprint $table) {
            $table->id();
            $table->foreignId('loan_id')->constrained();
            $table->decimal('amount', 14, 2);
            $table->decimal('interest', 14, 2);
            $table->decimal('principal', 14, 2);
            $table->decimal('running_balance', 14, 2);
            $table->string('type')->default('OR');
            $table->string('reference_number');
            $table->string('remarks')->nullable();
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
        Schema::dropIfExists('loan_payments');
    }
};
