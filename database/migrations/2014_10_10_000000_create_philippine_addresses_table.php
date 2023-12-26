<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        try {
            DB::unprepared(file_get_contents(database_path('migrations/location.sql')));
        } catch (\Throwable $e) {
            dd(str($e->getMessage())->before("\t"));
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('philippine_addresses');
    }
};
