<?php

use App\Models\CashCollectibleBilling;
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
        Schema::create('cash_collectible_billing_payments', function (Blueprint $table) {
            $table->id();
            $table->foreignIdFor(CashCollectibleBilling::class)->constrained(indexName: 'cash_collectible_billing_foreign');
            $table->foreignId('account_id')->constrained();
            $table->foreignIdFor(Member::class)->nullable()->constrained();
            $table->string('payee');
            $table->decimal('amount_due', 18, 4);
            $table->decimal('amount_paid', 18, 4);
            $table->boolean('posted')->default(false);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('cash_collectible_billing_payments');
    }
};
