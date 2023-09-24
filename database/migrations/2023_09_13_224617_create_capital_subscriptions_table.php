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
            $table->string('code');
            $table->integer('number_of_terms')->default(12);
            $table->integer('number_of_shares');
            $table->decimal('amount_subscribed', 14, 2)->default(1000);
            $table->decimal('initial_amount_paid', 14, 2);
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
