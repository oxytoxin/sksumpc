<?php

use App\Models\CapitalSubscription;
use App\Models\CapitalSubscriptionBilling;
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
        Schema::create('capital_subscription_billing_payments', function (Blueprint $table) {
            $table->id();
            $table->foreignIdFor(CapitalSubscriptionBilling::class)->constrained(indexName: 'capital_subscription_billing_foreign');
            $table->foreignIdFor(CapitalSubscription::class)->constrained(indexName: 'capital_subscription_foreign');
            $table->foreignIdFor(Member::class)->constrained();
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
        Schema::dropIfExists('capital_subscription_billing_payments');
    }
};
