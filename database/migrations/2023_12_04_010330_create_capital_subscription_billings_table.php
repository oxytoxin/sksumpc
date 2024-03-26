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
        Schema::create('capital_subscription_billings', function (Blueprint $table) {
            $table->id();
            $table->date('date');
            $table->string('billable_date')->virtualAs("DATE_FORMAT(date, '%M %Y')");
            $table->foreignId('payment_type_id')->nullable()->constrained();
            $table->string('reference_number')->nullable();
            $table->string('name')->nullable();
            $table->string('or_number')->nullable();
            $table->foreignId('cashier_id')->nullable()->constrained('users', 'id')->nullOnDelete();
            $table->boolean('posted')->default(false);
            $table->boolean('for_or')->default(false);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('capital_subscription_billings');
    }
};
