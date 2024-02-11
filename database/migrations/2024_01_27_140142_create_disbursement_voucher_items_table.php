<?php

use App\Models\Account;
use App\Models\DisbursementVoucher;
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
        Schema::create('disbursement_voucher_items', function (Blueprint $table) {
            $table->id();
            $table->foreignIdFor(DisbursementVoucher::class)->constrained()->cascadeOnDelete()->cascadeOnUpdate();
            $table->foreignIdFor(Account::class)->constrained()->cascadeOnDelete()->cascadeOnUpdate();
            $table->decimal('credit', 18, 4)->nullable();
            $table->decimal('debit', 18, 4)->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('disbursement_voucher_items');
    }
};
