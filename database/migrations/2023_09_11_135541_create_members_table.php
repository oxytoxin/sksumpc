<?php

use App\Models\Barangay;
use App\Models\Municipality;
use App\Models\Province;
use App\Models\Region;
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
        Schema::create('members', function (Blueprint $table) {
            $table->id();
            $table->string('mpc_code')->index()->unique()->nullable();
            $table->foreignId('member_type_id')->nullable()->constrained();
            $table->foreignId('division_id')->nullable()->constrained();
            $table->foreignId('user_id')->nullable()->constrained();
            $table->string('first_name');
            $table->string('last_name');
            $table->string('middle_name')->nullable();
            $table->string('middle_initial')->nullable();
            $table->string('full_name')->virtualAs("CONCAT(first_name, ' ', IFNULL(CONCAT(middle_initial, '. '), ''), last_name)");
            $table->string('alt_full_name')->virtualAs("CONCAT(last_name, ', ', first_name, IFNULL(CONCAT(' ', middle_initial, '.'), ''))");
            $table->string('tin')->nullable();
            $table->foreignId('gender_id')->nullable()->constrained();
            $table->foreignId('civil_status_id')->nullable()->constrained();
            $table->foreignId('patronage_status_id')->default(1)->constrained();
            $table->date('membership_date')->default(DB::raw('(CURRENT_DATE)'));
            $table->date('contact')->nullable();
            $table->date('dob')->nullable();
            $table->string('place_of_birth')->nullable();
            // $table->integer('age')->virtualAs(DB::raw("TIMESTAMPDIFF(YEAR,dob,(CURRENT_DATE))"))->nullable();
            $table->string('address')->nullable();
            $table->foreignId('occupation_id')->nullable()->constrained();
            $table->string('present_employer')->nullable();
            $table->string('highest_educational_attainment')->nullable();
            $table->json('dependents')->default(DB::raw('(JSON_ARRAY())'));
            $table->integer('dependents_count')->virtualAs('JSON_LENGTH(dependents)');
            $table->foreignId('religion_id')->nullable()->constrained();
            $table->decimal('annual_income', 18, 4, true)->nullable();
            $table->decimal('other_income_sources', 18, 4, true)->nullable();
            $table->foreignIdFor(Region::class)->nullable()->constrained();
            $table->foreignIdFor(Province::class)->nullable()->constrained();
            $table->foreignIdFor(Municipality::class)->nullable()->constrained();
            $table->foreignIdFor(Barangay::class)->nullable()->constrained();
            $table->timestamp('terminated_at')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('members');
    }
};
