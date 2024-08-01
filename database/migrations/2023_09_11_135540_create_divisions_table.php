<?php

use App\Models\Division;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('divisions', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->timestamps();
        });

        Division::insert(
            [
                ['name' => 'ACCESS'],
                ['name' => 'ADMINISTRATION'],
                ['name' => 'ASSOCIATE'],
                ['name' => 'BAGUMBAYAN'],
                ['name' => 'ISULAN'],
                ['name' => 'KALAMANSIG'],
                ['name' => 'LUTAYAN'],
                ['name' => 'TACURONG'],
                ['name' => 'RETIRED'],
                ['name' => 'SKSU-MPC'],
                ['name' => 'NOT CONNECTED'],
            ]
        );
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('divisions');
    }
};
