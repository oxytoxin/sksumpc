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
            $table->decimal('interest', 7, 3);
            $table->string('code');
            $table->string('name');
            $table->timestamps();
        });

        LoanType::create([
            'interest' => 0.01,
            'code' => 'CL',
            'name' => 'Commodity Loan'
        ]);
        LoanType::create([
            'interest' => 0.01,
            'code' => 'PL',
            'name' => 'Providential Loan'
        ]);
        LoanType::create([
            'interest' => 0.01,
            'code' => 'EL',
            'name' => 'Emergency Loan'
        ]);
        LoanType::create([
            'interest' => 0.01,
            'code' => 'RL',
            'name' => 'Regular Loan'
        ]);
        LoanType::create([
            'interest' => 0.01,
            'code' => 'ACL',
            'name' => 'Additional Commodity Loan'
        ]);
        LoanType::create([
            'interest' => 0.01,
            'code' => 'SL',
            'name' => 'Special Loan'
        ]);
        LoanType::create([
            'interest' => 0.015,
            'code' => 'KL',
            'name' => 'Kabuhayan Loan'
        ]);
        LoanType::create([
            'interest' => 0.01,
            'code' => 'FNPL',
            'name' => 'Fly Now Pay Later Loan'
        ]);
        LoanType::create([
            'interest' => 0.01,
            'code' => 'LBP-ATM',
            'name' => 'LBP-ATM CARD Loan'
        ]);
        LoanType::create([
            'interest' => 0.01,
            'code' => 'LBP-RL',
            'name' => 'LBP-Regular Loan'
        ]);
        LoanType::create([
            'interest' => 0.01,
            'code' => 'RES-LBP ATM',
            'name' => 'Restructuring-LBP ATM'
        ]);
        LoanType::create([
            'interest' => 0.01,
            'code' => 'RES-KL',
            'name' => 'Restructuring-KL'
        ]);
        LoanType::create([
            'interest' => 0.01,
            'code' => 'RES-PL',
            'name' => 'Restructuring-PL'
        ]);
        LoanType::create([
            'interest' => 0.01,
            'code' => 'RES-LBP CL',
            'name' => 'RES-LBP Commodity Loan'
        ]);
        LoanType::create([
            'interest' => 0.01,
            'code' => 'RES-RL',
            'name' => 'Restructuring-RL'
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
