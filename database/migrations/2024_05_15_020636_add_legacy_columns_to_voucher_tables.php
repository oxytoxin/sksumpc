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
        Schema::table('journal_entry_vouchers', function (Blueprint $table) {
            $table->boolean('is_legacy')->after('bookkeeper_id')->default(false);
        });
        Schema::table('journal_entry_voucher_items', function (Blueprint $table) {
            $table->json('details')->after('debit')->default(DB::raw('(JSON_ARRAY())'));
        });

        Schema::table('disbursement_vouchers', function (Blueprint $table) {
            $table->boolean('is_legacy')->after('bookkeeper_id')->default(false);
        });
        Schema::table('disbursement_voucher_items', function (Blueprint $table) {
            $table->json('details')->after('debit')->default(DB::raw('(JSON_ARRAY())'));
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
    }
};
