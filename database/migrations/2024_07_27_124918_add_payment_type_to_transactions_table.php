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
        Schema::table('transactions', function (Blueprint $table) {
            $table->foreignId('payment_type_id')->after('member_id')->nullable()->constrained()->cascadeOnDelete();
        });
        \App\Models\Transaction::where('transaction_type_id', 1)->update(['payment_type_id' => 1]);
        \App\Models\Transaction::where('transaction_type_id', 2)->update(['payment_type_id' => 4]);
        \App\Models\Transaction::where('transaction_type_id', 3)->update(['payment_type_id' => 2]);
        \App\Models\Transaction::query()
            ->where('credit', '<', 0)
            ->orWhere('debit', '<', 0)
            ->each(fn ($t) => $t->update(['debit' => abs($t->debit), 'credit' => abs($t->credit)]));
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('transactions', function (Blueprint $table) {
            //
        });
    }
};
