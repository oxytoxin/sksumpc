<?php

use App\Models\MemberType;
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
        Schema::create('member_types', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->timestamps();
        });

        MemberType::create([
            'name' => 'REGULAR'
        ]);
        MemberType::create([
            'name' => 'ASSOCIATE'
        ]);
        MemberType::create([
            'name' => 'LABORATORY'
        ]);
        MemberType::create([
            'name' => 'RETIREE'
        ]);
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('member_types');
    }
};
