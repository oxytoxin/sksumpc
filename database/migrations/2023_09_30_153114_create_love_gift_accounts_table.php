<?php

use App\Models\Member;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('love_gift_accounts', function (Blueprint $table) {
            $table->id();
            $table->foreignIdFor(Member::class)->constrained();
            $table->string('number')->index()->unique();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('love_gift_accounts');
    }
};
