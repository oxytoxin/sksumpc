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
        Schema::create('mso_subscriptions', function (Blueprint $table) {
            $table->id();
            $table->tinyInteger('type');
            $table->foreignId('account_id')->constrained();
            $table->foreignId('member_id')->nullable()->constrained();
            $table->string('payee');
            $table->decimal('amount', 18, 4);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('mso_subscriptions');
    }
};
