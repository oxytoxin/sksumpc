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
            $table->foreignId('member_id')->constrained();
            $table->foreignId('payment_type_id')->constrained();
            $table->decimal('amount', 18, 4);
            $table->decimal('deposit', 18, 4)->nullable()->virtualAs('IF(amount >= 0, amount, null)');
            $table->decimal('withdrawal', 18, 4)->nullable()->virtualAs('IF(amount < 0, amount * -1, null)');
            $table->decimal('running_balance', 18, 4);
            $table->string('reference_number');
            $table->string('remarks')->nullable();
            $table->date('transaction_date')->default(DB::raw('(CURRENT_DATE)'));
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
