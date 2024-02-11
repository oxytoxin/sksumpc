<?php

use App\Models\Account;
use App\Models\Member;
use App\Models\TransactionType;
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
        Schema::create('transactions', function (Blueprint $table) {
            $table->id();
            $table->foreignIdFor(TransactionType::class)->constrained();
            $table->foreignIdFor(Account::class)->constrained()->cascadeOnDelete()->cascadeOnUpdate();
            $table->foreignIdFor(Member::class)->nullable()->constrained()->nullOnDelete()->cascadeOnUpdate();
            $table->string('reference_number');
            $table->string('remarks')->nullable();
            $table->decimal('credit', 18, 4)->nullable();
            $table->decimal('debit', 18, 4)->nullable();
            $table->date('transaction_date')->default(DB::raw('(CURRENT_DATE)'));
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('transactions');
    }
};
