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
        Schema::table('membership_statuses', function (Blueprint $table) {
            $table->string('termination_voucher_number')->nullable()->after('bod_resolution');
            $table->decimal('capital_amount_closed', 14, 4)->default(0)->after('termination_voucher_number');
        });
    }
};
