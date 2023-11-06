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
        Schema::create('capital_subscription_payments', function (Blueprint $table) {
            $table->id();
            $table->foreignId('capital_subscription_id')->constrained()->cascadeOnDelete();
            $table->foreignId('payment_type_id');
            $table->decimal('amount', 14, 2);
            $table->decimal('deposit', 14, 2)->nullable()->virtualAs('IF(amount >= 0, amount, null)');
            $table->decimal('withdrawal', 14, 2)->nullable()->virtualAs('IF(amount < 0, amount * -1, null)');
            $table->decimal('running_balance', 14, 2);
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
        Schema::dropIfExists('capital_subscription_payments');
    }
};
