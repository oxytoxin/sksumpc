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
            $table->foreignId('payment_type_id');
            $table->string('reference_number');
            $table->decimal('amount', 14, 2);
            $table->decimal('deposit', 14, 2)->nullable()->virtualAs('IF(amount >= 0, amount, null)');
            $table->decimal('withdrawal', 14, 2)->nullable()->virtualAs('IF(amount < 0, amount * -1, null)');
            $table->decimal('interest_rate', 7, 4);
            $table->decimal('interest', 14, 2)->default(0);
            $table->date('transaction_date');
            $table->date('interest_date')->nullable();
            $table->integer('number_of_days')->virtualAs('COALESCE(DATEDIFF(COALESCE(interest_date, CURDATE()), transaction_date), 0)');
            $table->decimal('balance', 14, 2);
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
