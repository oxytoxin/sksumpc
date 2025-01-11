<?php

use App\Models\AccountType;
use App\Models\Member;
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
        Schema::create('accounts', function (Blueprint $table) {
            $table->id();
            $table->foreignIdFor(AccountType::class)->constrained();
            $table->foreignIdFor(Member::class)->nullable()->constrained();
            $table->foreignId('parent_id')->nullable()->index();
            $table->tinyText('name');
            $table->string('number')->index();
            $table->string('tag')->index()->nullable();
            $table->nullableMorphs('accountable');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('accounts');
    }
};
