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
        Schema::table('capital_subscription_billings', function (Blueprint $table) {
            $table->foreignId('member_subtype_id')->after('member_type_id')->nullable()->constrained();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('capital_subscription_billings', function (Blueprint $table) {
            $table->dropForeign('capital_subscription_billings_member_subtype_id_foreign');
            $table->dropColumn('member_subtype_id');
        });
    }
};
