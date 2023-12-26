<?php

use App\Models\Division;
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
        Schema::create('divisions', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->timestamps();
        });

        Division::create([
            'name' => 'ACCESS Campus',
        ]);
        Division::create([
            'name' => 'Isulan Campus',
        ]);
        Division::create([
            'name' => 'Lutayan Campus',
        ]);
        Division::create([
            'name' => 'Kalamansig Campus',
        ]);
        Division::create([
            'name' => 'Palimbang Campus',
        ]);
        Division::create([
            'name' => 'Tacurong Campus',
        ]);
        Division::create([
            'name' => 'ACCESS-ADMIN',
        ]);
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('divisions');
    }
};
