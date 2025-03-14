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
            $table->boolean('is_active');
            $table->string('code')->nullable();
            $table->integer('number_of_terms')->default(12);
            $table->decimal('number_of_shares', 18, 4);
            $table->decimal('amount_subscribed', 18, 4)->default(1000);
            $table->decimal('monthly_payment', 18, 4)->nullable();
            $table->decimal('initial_amount_paid', 18, 4)->nullable();
            $table->decimal('par_value');
            $table->date('transaction_date')->default(DB::raw('(CURRENT_DATE)'));
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
