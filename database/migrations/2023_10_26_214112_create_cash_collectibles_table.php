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
        Schema::create('cash_collectibles', function (Blueprint $table) {
            $table->id();
            $table->foreignIdFor(\App\Models\CashCollectibleCategory::class)->constrained();
            $table->string('name');
            $table->boolean('has_inventory')->default(false);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('cash_collectibles');
    }
};
