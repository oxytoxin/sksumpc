<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * Drops the columns that were moved to member_credit_and_backgrounds,
     * and drops the now-redundant JSON columns from that table.
     */
    public function up(): void
    {
        Schema::table('members', function (Blueprint $table) {
            if (Schema::hasColumn('members', 'civil_status_id')) {
                $table->dropForeign(['civil_status_id']);
            }
            if (Schema::hasColumn('members', 'occupation_id')) {
                $table->dropForeign(['occupation_id']);
            }

            $table->dropColumn([
                'civil_status_id',
                'occupation_id',
                'occupation_description',
                'present_employer',
                'highest_educational_attainment',
                'annual_income',
                'monthly_salary',
                'other_income_sources',
                'dependents',
                'dependents_count',
            ]);
        });

        Schema::table('member_credit_and_backgrounds', function (Blueprint $table) {
            // Drop the old JSON columns now that data lives in dedicated columns.
            $table->dropColumn([
                'spouse',
                'employment_verification',
                'income_verification',
            ]);
        });
    }
};
