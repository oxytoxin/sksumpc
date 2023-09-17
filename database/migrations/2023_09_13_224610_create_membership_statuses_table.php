<?php

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
        Schema::create('membership_statuses', function (Blueprint $table) {
            $table->id();
            $table->foreignId('member_id')->constrained();
            $table->tinyInteger('type');
            $table->string('bod_resolution')->nullable();
            $table->date('effectivity_date');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('membership_statuses');
    }
};
