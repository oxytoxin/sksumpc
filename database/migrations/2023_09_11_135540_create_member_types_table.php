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
        Schema::create('member_types', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->decimal('surcharge_rate', 7, 4);
            $table->decimal('par_value', 8, 2);
            $table->decimal('default_number_of_shares', 8, 2);
            $table->decimal('default_amount_subscribed', 18, 4);
            $table->decimal('minimum_initial_payment', 18, 4);
            $table->integer('initial_number_of_terms');
            $table->integer('additional_number_of_terms');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('member_types');
    }
};
