<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('members', function (Blueprint $table) {
            $table->decimal('monthly_salary', 12, 2)->nullable()->after('annual_income');
            $table->json('beneficiaries')->default(DB::raw('(JSON_ARRAY())'))->after('dependents');
            $table->integer('beneficiaries_count')->virtualAs('JSON_LENGTH(beneficiaries)')->after('beneficiaries');
        });
    }
};
