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
            $table->boolean('buy_out')->default(false);
            $table->foreignId('loan_id')->constrained();
            $table->decimal('amount', 18, 4);
            $table->decimal('interest_payment', 18, 4)->default(0);
            $table->decimal('principal_payment', 18, 4)->default(0);
            $table->decimal('surcharge_payment', 18, 4)->default(0);
            $table->foreignId('payment_type_id')->constrained();
            $table->string('reference_number');
            $table->string('remarks')->nullable();
            $table->date('transaction_date')->default(DB::raw('CURDATE()'));
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
