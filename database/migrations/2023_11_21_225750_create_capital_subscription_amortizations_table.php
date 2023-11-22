<?php

use App\Models\CapitalSubscription;
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
        Schema::create('capital_subscription_amortizations', function (Blueprint $table) {
            $table->id();
            $table->foreignIdFor(CapitalSubscription::class)->constrained(indexName: 'amortizations_capital_subscription_id_foreign');
            $table->string('billable_date')->virtualAs("DATE_FORMAT(due_date, '%M %Y')");
            $table->date('due_date');
            $table->integer('term');
            $table->decimal('amount', 14, 2)->nullable();
            $table->decimal('amount_paid', 14, 2)->nullable();
            $table->decimal('arrears', 14, 2, true)->virtualAs('amount - amount_paid');
            $table->string('remarks')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('capital_subscription_amortizations');
    }
};
