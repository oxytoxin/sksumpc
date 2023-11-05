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
        Schema::create('capital_subscriptions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('member_id')->constrained();
            $table->boolean('is_common');
            $table->string('code')->nullable();
            $table->integer('number_of_terms')->default(12);
            $table->decimal('number_of_shares', 14, 2);
            $table->decimal('amount_subscribed', 14, 2)->default(1000);
            $table->decimal('monthly_payment', 14, 2)->nullable();
            $table->decimal('initial_amount_paid', 14, 2)->nullable();
            $table->decimal('par_value');
            $table->date('transaction_date');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('capital_subscriptions');
    }
};
