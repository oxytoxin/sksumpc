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
        Schema::create('time_deposits', function (Blueprint $table) {
            $table->id();
            $table->foreignId('member_id')->constrained();
            $table->foreignId('payment_type_id');
            $table->string('reference_number');
            $table->string('identifier')->virtualAs("concat('TD-', reference_number)");
            $table->date('maturity_date');
            $table->date('withdrawal_date')->nullable();
            $table->decimal('amount', 14, 2);
            $table->integer('number_of_days')->default(180);
            $table->decimal('maturity_amount', 14, 2);
            $table->decimal('interest_rate', 7, 2);
            $table->decimal('interest', 14, 2)->virtualAs('maturity_amount - amount');
            $table->date('transaction_date');
            $table->string('tdc_number')->unique();
            $table->foreignId('cashier_id')->nullable()->constrained('users', 'id')->nullOnDelete();

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('time_deposits');
    }
};
