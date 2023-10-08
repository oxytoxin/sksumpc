<?php

use App\Models\SystemConfiguration;
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
        Schema::create('system_configurations', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->json('content')->default(DB::raw('(JSON_ARRAY())'));
            $table->timestamps();
        });

        SystemConfiguration::create([
            'name' => 'Load Deductions',
            'content' => [
                [
                    'name' => "Service Fee",
                    'percentage' => 0.005,
                ],
                [
                    'name' => "CBU-Common",
                    'percentage' => 0.02,
                ],
                [
                    'name' => "Imprest Savings",
                    'percentage' => 0.01,
                ],
                [
                    'name' => "Insurance-LOAN",
                    'percentage' => 0.0192,
                ],
            ]
        ]);
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('system_configurations');
    }
};
