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
                ['name' => 'ALLIANCE'],
                ['name' => 'ASSEMBLY OF GOD'],
                ['name' => 'BAPTIST'],
                ['name' => 'BORN AGAIN'],
                ['name' => 'CHRISTIAN CHURCH'],
                ['name' => 'EVANGELIST'],
                ['name' => 'FOURSQUARE'],
                ['name' => 'I.F.I'],
                ['name' => 'IGLESIA NI CRISTO'],
                ['name' => 'ISLAM'],
                ['name' => 'LCBC-PILIPINISTA'],
                ['name' => 'PENTECOSTAL'],
                ['name' => 'PROTESTANT'],
                ['name' => 'ROMAN CATHOLIC'],
                ['name' => 'SDA'],
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
