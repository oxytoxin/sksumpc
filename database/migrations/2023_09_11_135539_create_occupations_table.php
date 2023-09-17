<?php

use App\Models\Occupation;
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
        Schema::create('occupations', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->timestamps();
        });
        $now = now();
        Occupation::insert(
            [
                [
                    'name' => "TEACHING",
                    'created_at' => $now,
                    'updated_at' => $now,
                ],
                [
                    'name' => "NON TEACHING",
                    'created_at' => $now,
                    'updated_at' => $now,
                ],
                [
                    'name' => "BUSINESSMAN",
                    'created_at' => $now,
                    'updated_at' => $now,
                ],
                [
                    'name' => "COOP STAFF/TELLER",
                    'created_at' => $now,
                    'updated_at' => $now,
                ],
                [
                    'name' => "FARMER",
                    'created_at' => $now,
                    'updated_at' => $now,
                ],
                [
                    'name' => "GOVERNMENT EMPLOYEE",
                    'created_at' => $now,
                    'updated_at' => $now,
                ],
                [
                    'name' => "HOUSEWIFE",
                    'created_at' => $now,
                    'updated_at' => $now,
                ],
                [
                    'name' => "NURSE",
                    'created_at' => $now,
                    'updated_at' => $now,
                ],
                [
                    'name' => "PRIVATE EMPLOYEE",
                    'created_at' => $now,
                    'updated_at' => $now,
                ],
                [
                    'name' => "SECURITY",
                    'created_at' => $now,
                    'updated_at' => $now,
                ],
                [
                    'name' => "STUDENT",
                    'created_at' => $now,
                    'updated_at' => $now,
                ],
            ]
        );
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('occupations');
    }
};
