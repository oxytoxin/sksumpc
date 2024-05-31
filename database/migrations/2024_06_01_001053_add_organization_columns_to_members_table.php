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
        Schema::table('members', function (Blueprint $table) {
            $table->boolean('is_organization')->default(false)->after('mpc_code');
            $table->json('member_ids')->default(DB::raw('(JSON_ARRAY())'))->after('mpc_code');
            $table->string('last_name')->nullable()->change();
            $table->dropColumn('full_name');
            $table->dropColumn('alt_full_name');
        });

        Schema::table('members', function (Blueprint $table) {
            $table->string('full_name')->virtualAs("IFNULL(CONCAT(first_name, ' ', IFNULL(CONCAT(middle_initial, '. '), ''), last_name), first_name)")->after('middle_initial');
            $table->string('alt_full_name')->virtualAs("IFNULL(CONCAT(last_name, ', ', first_name, IFNULL(CONCAT(' ', middle_initial, '.'), '')), first_name)")->after('middle_initial');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('members', function (Blueprint $table) {
            //
        });
    }
};
