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
        // 1. Add columns moved from the members table.
        Schema::table('member_credit_and_backgrounds', function (Blueprint $table) {
            $table->foreignId('civil_status_id')
                ->after('member_id')
                ->nullable()
                ->constrained('civil_statuses')
                ->nullOnDelete();
            $table->foreignId('occupation_id')
                ->after('civil_status_id')
                ->nullable()
                ->constrained('occupations')
                ->nullOnDelete();
            $table->string('occupation_description')->after('occupation_id')->nullable();
            $table->string('present_employer')->after('occupation_description')->nullable();
            $table->string('highest_educational_attainment')->after('present_employer')->nullable();
            $table->decimal('annual_income', 18, 2)->after('highest_educational_attainment')->nullable();
            $table->decimal('monthly_salary', 12, 2)->after('annual_income')->nullable();
            $table->string('other_income_sources', 255)->after('monthly_salary')->nullable();
            $table->json('dependents')->after('other_income_sources')->nullable();
        });

        // 2. Deconstruct the existing JSON columns into dedicated columns.
        //    NOTE: income_verification.annual_income is deconstructed into a
        //    temp column (`_income_annual`) to avoid colliding with the
        //    `annual_income` column added above. It is merged in step 5.
        Schema::table('member_credit_and_backgrounds', function (Blueprint $table) {
            // Spouse (was: spouse json)
            $table->string('spouse_name')->after('school')->nullable();
            $table->string('spouse_nickname')->after('spouse_name')->nullable();
            $table->string('spouse_middle_name')->after('spouse_nickname')->nullable();
            $table->date('spouse_date_of_birth')->after('spouse_middle_name')->nullable();
            $table->integer('spouse_age')->after('spouse_date_of_birth')->nullable();
            $table->string('spouse_contact_number')->after('spouse_age')->nullable();
            $table->string('spouse_civil_status')->after('spouse_contact_number')->nullable();
            $table->string('spouse_nationality')->after('spouse_civil_status')->nullable();
            $table->string('spouse_address')->after('spouse_nationality')->nullable();
            $table->string('spouse_highest_educational_attainment')->after('spouse_address')->nullable();
            $table->string('spouse_school')->after('spouse_highest_educational_attainment')->nullable();

            // Employment verification (was: employment_verification json)
            $table->string('employer')->after('dependents')->nullable();
            $table->string('office_address')->after('employer')->nullable();
            $table->string('business_form')->after('office_address')->nullable();
            $table->string('nature_of_business')->after('business_form')->nullable();
            $table->integer('year_connected')->after('nature_of_business')->nullable();
            $table->string('position')->after('year_connected')->nullable();
            $table->string('employment_status')->after('position')->nullable();

            // Income verification (was: income_verification json)
            $table->decimal('basic_salary', 14, 2)->after('employment_status')->nullable();
            $table->decimal('allowances', 14, 2)->after('basic_salary')->nullable();
            $table->decimal('business_income', 14, 2)->after('allowances')->nullable();
            $table->decimal('other_income', 14, 2)->after('business_income')->nullable();
            $table->decimal('monthly_income', 14, 2)->after('other_income')->nullable();
            $table->decimal('_income_annual', 14, 2)->after('monthly_income')->nullable();
        });

        // 3. Backfill: deconstruct existing JSON columns.
        $this->backfillFromJsonColumns();

        // 4. Backfill: copy values from the members table for the moved columns.
        $this->backfillFromMembersTable();

        // 5. Merge `_income_annual` into `annual_income`, then drop the temp column.
        DB::table('member_credit_and_backgrounds')
            ->whereNotNull('_income_annual')
            ->update(['annual_income' => DB::raw('_income_annual')]);

        Schema::table('member_credit_and_backgrounds', function (Blueprint $table) {
            $table->dropColumn('_income_annual');
        });
    }

    /**
     * Deconstruct the spouse, employment_verification, and income_verification
     * JSON columns into their new dedicated columns.
     */
    private function backfillFromJsonColumns(): void
    {
        DB::table('member_credit_and_backgrounds')
            ->orderBy('id')
            ->chunkById(200, function ($cibis) {
                foreach ($cibis as $cibi) {
                    $updates = [];

                    $spouse = $this->decodeJson($cibi->spouse);
                    if ($spouse !== null) {
                        $updates = array_merge($updates, [
                            'spouse_name' => $spouse['name'] ?? null,
                            'spouse_nickname' => $spouse['nickname'] ?? null,
                            'spouse_middle_name' => $spouse['middle_name'] ?? null,
                            'spouse_date_of_birth' => $this->normalizeDate($spouse['date_of_birth'] ?? null),
                            'spouse_age' => $this->nullableInt($spouse['age'] ?? null),
                            'spouse_contact_number' => $spouse['contact_number'] ?? null,
                            'spouse_civil_status' => $spouse['civil_status'] ?? null,
                            'spouse_nationality' => $spouse['nationality'] ?? null,
                            'spouse_address' => $spouse['address'] ?? null,
                            'spouse_highest_educational_attainment' => $spouse['highest_educational_attainment'] ?? null,
                            'spouse_school' => $spouse['school'] ?? null,
                        ]);
                    }

                    $employment = $this->decodeJson($cibi->employment_verification);
                    if ($employment !== null) {
                        $updates = array_merge($updates, [
                            'employer' => $employment['employer'] ?? null,
                            'office_address' => $employment['office_address'] ?? null,
                            'business_form' => $employment['business_form'] ?? null,
                            'nature_of_business' => $employment['nature_of_business'] ?? null,
                            'year_connected' => $this->nullableInt($employment['year_connected'] ?? null),
                            'position' => $employment['position'] ?? null,
                            'employment_status' => $employment['employment_status'] ?? null,
                        ]);
                    }

                    $income = $this->decodeJson($cibi->income_verification);
                    if ($income !== null) {
                        $updates = array_merge($updates, [
                            'basic_salary' => $this->nullableFloat($income['basic_salary'] ?? null),
                            'allowances' => $this->nullableFloat($income['allowances'] ?? null),
                            'business_income' => $this->nullableFloat($income['business_income'] ?? null),
                            'other_income' => $this->nullableFloat($income['other_income'] ?? null),
                            'monthly_income' => $this->nullableFloat($income['monthly_income'] ?? null),
                            '_income_annual' => $this->nullableFloat($income['annual_income'] ?? null),
                        ]);
                    }

                    if ($updates !== []) {
                        DB::table('member_credit_and_backgrounds')
                            ->where('id', $cibi->id)
                            ->update($updates);
                    }
                }
            });
    }

    /**
     * Copy the moved columns from the members table into the CIBI table.
     * Existing non-null CIBI values are preserved (not overwritten).
     */
    private function backfillFromMembersTable(): void
    {
        // First, ensure every member has a CIBI row.
        $memberIds = DB::table('members')->pluck('id');
        foreach ($memberIds->chunk(500) as $ids) {
            DB::table('member_credit_and_backgrounds')
                ->insertOrIgnore(
                    collect($ids)
                        ->map(fn ($id) => ['member_id' => $id, 'created_at' => now(), 'updated_at' => now()])
                        ->toArray()
                );
        }

        // Copy the moved columns from members; only fill empty CIBI values.
        DB::table('member_credit_and_backgrounds')
            ->orderBy('id')
            ->chunkById(200, function ($cibis) {
                foreach ($cibis as $cibi) {
                    $member = DB::table('members')->where('id', $cibi->member_id)->first();
                    if (! $member) {
                        continue;
                    }

                    $updates = [];

                    foreach ([
                        'civil_status_id',
                        'occupation_id',
                        'occupation_description',
                        'present_employer',
                        'highest_educational_attainment',
                        'annual_income',
                        'monthly_salary',
                        'other_income_sources',
                    ] as $column) {
                        if (blank($cibi->{$column}) && ! blank($member->{$column})) {
                            $updates[$column] = $member->{$column};
                        }
                    }

                    // Dependents: always copy from the member (CIBI had no dependents before).
                    if (! blank($member->dependents)) {
                        $updates['dependents'] = $member->dependents;
                    }

                    // monthly_salary -> basic_salary (closest semantic match) when empty.
                    if (blank($cibi->basic_salary) && ! blank($member->monthly_salary)) {
                        $updates['basic_salary'] = $member->monthly_salary;
                    }

                    if ($updates !== []) {
                        DB::table('member_credit_and_backgrounds')
                            ->where('id', $cibi->id)
                            ->update($updates);
                    }
                }
            });
    }

    private function decodeJson(?string $value): ?array
    {
        if (blank($value)) {
            return null;
        }

        $decoded = json_decode($value, true);

        return is_array($decoded) ? $decoded : null;
    }

    private function normalizeDate(mixed $value): ?string
    {
        if (blank($value)) {
            return null;
        }

        try {
            return \Carbon\Carbon::parse($value)->toDateString();
        } catch (\Throwable) {
            return null;
        }
    }

    private function nullableInt(mixed $value): ?int
    {
        return filled($value) ? (int) $value : null;
    }

    private function nullableFloat(mixed $value): ?float
    {
        return filled($value) ? (float) $value : null;
    }
};
