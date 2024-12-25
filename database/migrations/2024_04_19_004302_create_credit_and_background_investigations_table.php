<?php

use App\Models\LoanApplication;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('credit_and_background_investigations', function (Blueprint $table) {
            $table->id();
            $table->foreignIdFor(LoanApplication::class)->constrained()->cascadeOnDelete();
            $table->json('details')->default(DB::raw('(JSON_ARRAY())'));
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('credit_and_background_investigations');
    }
};
