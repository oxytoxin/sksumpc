<?php

use App\Models\BalanceForwardedSummary;
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
        Schema::create('balance_forwarded_entries', function (Blueprint $table) {
            $table->id();
            $table->foreignIdFor(BalanceForwardedSummary::class)->constrained();
            $table->foreignIdFor(TrialBalanceEntry::class)->constrained();
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
        Schema::dropIfExists('balance_forwarded_entries');
    }
};
