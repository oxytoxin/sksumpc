<?php

use App\Models\CashCollectible;
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
            $table->string('name');
            $table->timestamps();
        });

        CashCollectible::create([
            'name' => 'Rice'
        ]);

        CashCollectible::create([
            'name' => 'Groceries'
        ]);
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('cash_collectibles');
    }
};