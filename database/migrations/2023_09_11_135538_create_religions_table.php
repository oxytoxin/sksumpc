<?php

use App\Models\Religion;
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
        Schema::create('religions', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->timestamps();
        });
        $now = now();
        Religion::insert(
            [
                [
                    'name' => "A/G",
                    'created_at' => $now,
                    'updated_at' => $now,
                ],
                [
                    'name' => "ALLIANCE",
                    'created_at' => $now,
                    'updated_at' => $now,
                ],
                [
                    'name' => "BAPTIST",
                    'created_at' => $now,
                    'updated_at' => $now,
                ],
                [
                    'name' => "ISLAM",
                    'created_at' => $now,
                    'updated_at' => $now,
                ],
                [
                    'name' => "PENTECOSTAL",
                    'created_at' => $now,
                    'updated_at' => $now,
                ],
                [
                    'name' => "PROTESTANT",
                    'created_at' => $now,
                    'updated_at' => $now,
                ],
                [
                    'name' => "ROMAN CATHOLIC",
                    'created_at' => $now,
                    'updated_at' => $now,
                ],
                [
                    'name' => "SDA",
                    'created_at' => $now,
                    'updated_at' => $now,
                ],
                [
                    'name' => "WESLEYAN",
                    'created_at' => $now,
                    'updated_at' => $now,
                ],
                [
                    'name' => "OTHERS",
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
        Schema::dropIfExists('religions');
    }
};
