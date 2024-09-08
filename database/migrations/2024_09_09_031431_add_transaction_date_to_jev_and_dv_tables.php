<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('journal_entry_voucher_items', function (Blueprint $table) {
            $table->date('transaction_date')->after('details')->default(DB::raw('(CURRENT_DATE)'));
        });
        Schema::table('disbursement_voucher_items', function (Blueprint $table) {
            $table->date('transaction_date')->after('debit')->default(DB::raw('(CURRENT_DATE)'));
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('jev_and_dv_tables', function (Blueprint $table) {
            //
        });
    }
};
