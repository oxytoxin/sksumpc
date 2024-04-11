<?php

use App\Models\CivilStatus;
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
        Schema::create('civil_statuses', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->timestamps();
        });

        CivilStatus::create([
            'name' => 'SINGLE',
        ]);
        CivilStatus::create([
            'name' => 'MARRIED',
        ]);
        CivilStatus::create([
            'name' => 'WIDOWED',
        ]);
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('civil_statuses');
    }
};
