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
        Schema::create('cash_collectible_subscriptions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('account_id')->constrained();
            $table->foreignId('member_id')->nullable()->constrained();
            $table->string('payee');
            $table->decimal('amount', 18, 4);
            $table->integer('number_of_terms');
            $table->decimal('billable_amount', 18, 4)->virtualAs('amount / number_of_terms');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('cash_collectible_subscriptions');
    }
};
