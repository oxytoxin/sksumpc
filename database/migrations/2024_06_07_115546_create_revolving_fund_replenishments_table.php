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
        Schema::create('revolving_fund_replenishments', function (Blueprint $table) {
            $table->id();
            $table->decimal('amount', 18, 4);
            $table->string('reference_number');
            $table->date('transaction_date')->default(DB::raw('(CURRENT_DATE)'));
            $table->timestamp('transaction_datetime')->virtualAs("TIMESTAMP(transaction_date, CAST(created_at as TIME))");
            $table->foreignId('cashier_id')->nullable()->constrained('users', 'id')->nullOnDelete();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('revolving_fund_replenishments');
    }
};
