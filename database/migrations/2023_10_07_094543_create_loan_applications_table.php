<?php

use App\Models\LoanApplication;
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
        Schema::create('loan_applications', function (Blueprint $table) {
            $table->id();
            $table->foreignId('member_id')->constrained();
            $table->foreignId('processor_id')->nullable()->constrained('users', 'id')->nullOnDelete();
            $table->foreignId('loan_type_id')->constrained();
            $table->decimal('desired_amount', 14, 2);
            $table->integer('number_of_terms');
            $table->string('reference_number')->nullable();
            $table->string('priority_number')->nullable();
            $table->string('purpose')->nullable();
            $table->decimal('monthly_payment', 16, 4);
            $table->smallInteger('status')->default(LoanApplication::STATUS_PROCESSING);
            $table->date('transaction_date');
            $table->json('approvals')->default('(JSON_ARRAY())');
            $table->string('remarks')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('loan_applications');
    }
};
