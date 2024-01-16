<?php

use App\Models\Loan;
use App\Models\LoanAmortization;
use App\Models\LoanBilling;
use App\Models\Member;
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
        Schema::create('loan_billing_payments', function (Blueprint $table) {
            $table->id();
            $table->foreignIdFor(LoanBilling::class)->constrained();
            $table->foreignIdFor(Member::class)->constrained();
            $table->foreignIdFor(Loan::class)->constrained();
            $table->decimal('amount_due', 18, 4);
            $table->decimal('amount_paid', 18, 4);
            $table->boolean('posted')->default(false);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('loan_billing_payments');
    }
};
