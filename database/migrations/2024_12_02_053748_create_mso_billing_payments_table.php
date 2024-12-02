<?php

use App\Models\Member;
use App\Models\MsoBilling;
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
        Schema::create('mso_billing_payments', function (Blueprint $table) {
            $table->id();
            $table->foreignIdFor(MsoBilling::class)->constrained(indexName: 'mso_billing_foreign');
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
        Schema::dropIfExists('mso_billing_payments');
    }
};
