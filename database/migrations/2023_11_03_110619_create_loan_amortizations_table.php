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
        Schema::create('loan_amortizations', function (Blueprint $table) {
            $table->id();
            $table->foreignId('loan_id')->constrained()->cascadeOnDelete();
            $table->date('date');
            $table->string('billable_date')->virtualAs("DATE_FORMAT(date, '%M %Y')");
            $table->integer('term');
            $table->integer('days');
            $table->decimal('amortization', 18, 4, true);
            $table->decimal('interest', 18, 4, true);
            $table->decimal('principal', 18, 4, true);
            $table->decimal('amount_paid', 18, 4, true)->nullable();
            $table->decimal('principal_payment', 18, 4, true)->virtualAs(' 
            CASE 
            WHEN (amount_paid - interest > 0) THEN amount_paid - interest 
            ELSE 0 
            END');
            $table->decimal('interest_payment', 18, 4, true)->virtualAs(' 
            CASE 
            WHEN (amount_paid > interest) THEN interest 
            ELSE IFNULL(amount_paid, 0) 
            END');
            $table->decimal('principal_balance', 18, 4, true)->virtualAs('principal - principal_payment');
            $table->decimal('interest_balance', 18, 4, true)->virtualAs('interest - interest_payment');
            $table->decimal('arrears', 18, 4, true)->virtualAs('interest + principal - amount_paid');
            $table->decimal('previous_balance', 18, 4, true);
            $table->decimal('outstanding_balance', 18, 4, true)->virtualAs('previous_balance - principal');
            $table->string('remarks')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('loan_amortizations');
    }
};
