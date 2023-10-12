<?php

use App\Models\LoanType;
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
        Schema::create('loan_types', function (Blueprint $table) {
            $table->id();
            $table->string('code');
            $table->string('name');
            $table->decimal('interest_rate', 7, 3)->default(0);
            $table->decimal('service_fee', 7, 4)->default(0);
            $table->decimal('cbu_common', 7, 4)->default(0);
            $table->decimal('imprest', 7, 4)->default(0);
            $table->decimal('insurance', 7, 4)->default(0);
            $table->timestamps();
        });

        LoanType::create([
            'code' => 'CL',
            'name' => 'Commodity Loan',
            'interest_rate' => 0.01,
            'service_fee' => 0.005,
            'cbu_common' => 0.02,
            'imprest' => 0.01,
            'insurance' => 0.0192
        ]);
        LoanType::create([
            'code' => 'PL',
            'name' => 'Providential Loan',
            'interest_rate' => 0.01,
            'service_fee' => 0.005,
            'cbu_common' => 0.02,
            'imprest' => 0.01,
            'insurance' => 0.0192
        ]);
        LoanType::create([
            'code' => 'EL',
            'name' => 'Emergency Loan',
            'interest_rate' => 0.01,
            'service_fee' => 0.003,
            'cbu_common' => 0.02,
            'imprest' => 0.01,
            'insurance' => 0.0192
        ]);
        LoanType::create([
            'code' => 'RL',
            'name' => 'Regular Loan',
            'interest_rate' => 0.01,
            'service_fee' => 0.005,
            'cbu_common' => 0.02,
            'imprest' => 0.01,
            'insurance' => 0.0192
        ]);
        LoanType::create([
            'code' => 'ACL',
            'name' => 'Additional Commodity Loan',
            'interest_rate' => 0.01,
            'service_fee' => 0.005,
            'cbu_common' => 0.02,
            'imprest' => 0.01,
            'insurance' => 0.0192
        ]);
        LoanType::create([
            'code' => 'SL',
            'name' => 'Special Loan',
            'interest_rate' => 0.01,
            'service_fee' => 0.003,
            'cbu_common' => 0.02,
            'imprest' => 0.01,
            'insurance' => 0.0192
        ]);
        LoanType::create([
            'code' => 'KL',
            'name' => 'Kabuhayan Loan',
            'interest_rate' => 0.015,
            'service_fee' => 0.005,
            'cbu_common' => 0.02,
            'imprest' => 0.01,
            'insurance' => 0.0192
        ]);
        LoanType::create([
            'code' => 'FNPL',
            'name' => 'Fly Now Pay Later Loan',
            'interest_rate' => 0.01,
            'service_fee' => 0.005,
            'cbu_common' => 0.02,
            'imprest' => 0.01,
            'insurance' => 0.0192
        ]);
        LoanType::create([
            'code' => 'LBP-ATM',
            'name' => 'LBP-ATM CARD Loan',
            'interest_rate' => 0.01,
            'service_fee' => 0.005,
            'cbu_common' => 0.02,
            'imprest' => 0.01,
            'insurance' => 0.0192
        ]);
        LoanType::create([
            'code' => 'LBP-RL',
            'name' => 'LBP-Regular Loan',
            'interest_rate' => 0.01,
            'service_fee' => 0.005,
            'cbu_common' => 0.02,
            'imprest' => 0.01,
            'insurance' => 0.0192
        ]);
        LoanType::create([
            'code' => 'RES-LBP ATM',
            'name' => 'Restructuring-LBP ATM',
            'interest_rate' => 0.01,
            'service_fee' => 0.005,
            'cbu_common' => 0.02,
            'imprest' => 0.01,
            'insurance' => 0.0192
        ]);
        LoanType::create([
            'code' => 'RES-KL',
            'name' => 'Restructuring-KL',
            'interest_rate' => 0.01,
            'service_fee' => 0.005,
            'cbu_common' => 0.02,
            'imprest' => 0.01,
            'insurance' => 0.0192
        ]);
        LoanType::create([
            'code' => 'RES-PL',
            'name' => 'Restructuring-PL',
            'interest_rate' => 0.01,
            'service_fee' => 0.005,
            'cbu_common' => 0.02,
            'imprest' => 0.01,
            'insurance' => 0.0192
        ]);
        LoanType::create([
            'code' => 'RES-LBP CL',
            'name' => 'RES-LBP Commodity Loan',
            'interest_rate' => 0.01,
            'service_fee' => 0.005,
            'cbu_common' => 0.02,
            'imprest' => 0.01,
            'insurance' => 0.0192
        ]);
        LoanType::create([
            'code' => 'RES-RL',
            'name' => 'Restructuring-RL',
            'interest_rate' => 0.01,
            'service_fee' => 0.005,
            'cbu_common' => 0.02,
            'imprest' => 0.01,
            'insurance' => 0.0192
        ]);
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('loan_types');
    }
};
