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
        Schema::table('accounts', function (Blueprint $table) {
            $table->timestamp('closed_at')->nullable()->after('updated_at');
            $table->string('close_remarks')->nullable()->after('closed_at');
        });
    }
};
