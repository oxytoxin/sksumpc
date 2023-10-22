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
            $table->string('type')->default('OR');
            $table->decimal('amount', 14, 2);
            $table->decimal('running_balance', 14, 2);
            $table->string('reference_number');
            $table->string('remarks')->nullable();
            $table->date('transaction_date');
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
