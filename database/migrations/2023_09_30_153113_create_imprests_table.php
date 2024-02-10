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
        Schema::create('imprests', function (Blueprint $table) {
            $table->id();
            $table->foreignId('member_id')->constrained();
            $table->foreignId('payment_type_id')->constrained();
            $table->string('reference_number');
            $table->decimal('amount', 18, 4);
            $table->decimal('deposit', 18, 4)->nullable()->virtualAs('IF(amount >= 0, amount, null)');
            $table->decimal('withdrawal', 18, 4)->nullable()->virtualAs('IF(amount < 0, amount * -1, null)');
            $table->decimal('interest_rate', 7, 4);
            $table->decimal('interest', 18, 4)->default(0);
            $table->date('transaction_date')->default(DB::raw('(CURRENT_DATE)'));
            $table->date('interest_date')->nullable();
            $table->decimal('balance', 18, 4)->default(0);
            $table->boolean('accrued')->default(false);
            $table->foreignId('cashier_id')->nullable()->constrained('users', 'id')->nullOnDelete();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('imprests');
    }
};
