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
        Schema::create('member_credit_and_backgrounds', function (Blueprint $table) {
            $table->id();
            $table->foreignId('member_id')->constrained()->onDelete('cascade');
            $table->string('nickname')->nullable();
            $table->string('nationality')->nullable();
            $table->string('school')->nullable();
            $table->json('spouse')->nullable();
            $table->json('children')->nullable();
            $table->json('employment_verification')->nullable();
            $table->json('income_verification')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('member_credit_and_backgrounds');
    }
};
