<?php

use App\Models\User;
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
        Schema::create('disbursement_vouchers', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('address')->nullable();
            $table->string('reference_number');
            $table->text('description');
            $table->date('transaction_date')->default(DB::raw('(CURRENT_DATE)'));
            $table->integer('cdj_column')->index();
            $table->foreignIdFor(User::class, 'bookkeeper_id');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('disbursement_vouchers');
    }
};