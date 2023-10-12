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
        Schema::create('loans', function (Blueprint $table) {
            $table->id();
            $table->foreignId('member_id')->constrained();
            $table->foreignId('loan_type_id')->constrained();
            $table->string('reference_number')->nullable();
            $table->decimal('gross_amount', 14, 2);
            $table->decimal('deductions_amount', 14, 2);
            $table->decimal('net_amount', 14, 2)->virtualAs('gross_amount - deductions_amount');
            $table->json('deductions')->default(DB::raw('(JSON_ARRAY())'));
            $table->integer('number_of_terms');
            $table->decimal('interest_rate', 7, 4);
            $table->decimal('interest', 14, 2);
            $table->decimal('monthly_payment', 14, 2);
            $table->date('release_date');
            $table->date('transaction_date');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('loans');
    }
};
