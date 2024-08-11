<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('member_officers_list', function (Blueprint $table) {
            $table->id();
            $table->foreignId('member_id')->constrained('members');
            $table->foreignId('position_id')->constrained('positions');
            $table->foreignId('officers_list_id')->constrained('officers_lists');
            $table->timestamps();
        });

        Schema::table('officers_lists', function (Blueprint $table) {
            $table->dropColumn('officers');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('member_officers_list');
    }
};
