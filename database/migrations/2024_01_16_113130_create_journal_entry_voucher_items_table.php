<?php

use App\Models\JournalEntryVoucher;
use App\Models\TrialBalanceEntry;
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
        Schema::create('journal_entry_voucher_items', function (Blueprint $table) {
            $table->id();
            $table->foreignIdFor(JournalEntryVoucher::class)->constrained()->cascadeOnDelete()->cascadeOnUpdate();
            $table->foreignIdFor(TrialBalanceEntry::class)->constrained()->cascadeOnDelete()->cascadeOnUpdate();
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
        Schema::dropIfExists('journal_entry_voucher_items');
    }
};
