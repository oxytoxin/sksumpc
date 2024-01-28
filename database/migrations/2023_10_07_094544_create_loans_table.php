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
            $table->string('account_number')->index()->unique();
            $table->foreignId('member_id')->constrained();
            $table->foreignId('loan_application_id')->constrained();
            $table->foreignId('loan_type_id')->constrained();
            $table->string('reference_number');
            $table->string('check_number')->nullable();
            $table->string('priority_number');
            $table->decimal('gross_amount', 18, 4);
            $table->decimal('net_amount', 18, 4)->virtualAs('gross_amount - deductions_amount');
            $table->json('deductions')->default(DB::raw('(JSON_ARRAY())'));
            $table->integer('number_of_terms');
            $table->decimal('interest_rate', 7, 4);
            $table->decimal('interest', 18, 4);
            $table->decimal('service_fee', 18, 4)->default(0);
            $table->decimal('cbu_amount', 18, 4)->default(0);
            $table->decimal('imprest_amount', 18, 4)->default(0);
            $table->decimal('insurance_amount', 18, 4)->default(0);
            $table->decimal('loan_buyout_interest', 18, 4)->default(0);
            $table->decimal('loan_buyout_principal', 18, 4)->default(0);
            $table->decimal('deductions_amount', 18, 4);
            $table->decimal('monthly_payment', 16, 4);
            $table->date('release_date');
            $table->date('transaction_date')->default(DB::raw('CURDATE()'));
            $table->boolean('posted')->default(false);
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
