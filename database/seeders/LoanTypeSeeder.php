<?php

namespace Database\Seeders;

use App\Models\LoanType;
use Illuminate\Database\Seeder;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class LoanTypeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {

        LoanType::create([
            'code' => 'RL',
            'name' => 'Regular Loan',
            'minimum_cbu' => 117000,
            'max_amount' => 350000,
            'interest_rate' => 0.01,
            'surcharge_rate' => 0.01,
            'service_fee' => 0.005,
            'cbu_common' => 0.02,
            'imprest' => 0.01,
            'insurance' => 0.0192,
        ]);
        LoanType::create([
            'code' => 'EL',
            'name' => 'Emergency Loan',
            'max_amount' => 30000,
            'interest_rate' => 0.01,
            'surcharge_rate' => 0.01,
            'service_fee' => 0.003,
            'cbu_common' => 0.02,
            'imprest' => 0.01,
            'insurance' => 0.0192,
        ]);
        LoanType::create([
            'code' => 'PL',
            'name' => 'Providential Loan',
            'minimum_cbu' => 23334,
            'max_amount' => 70000,
            'interest_rate' => 0.01,
            'surcharge_rate' => 0.01,
            'service_fee' => 0.005,
            'cbu_common' => 0.02,
            'imprest' => 0.01,
            'insurance' => 0.0192,
        ]);
        LoanType::create([
            'code' => 'CL',
            'name' => 'Commodity Loan',
            'minimum_cbu' => 50000,
            'max_amount' => 150000,
            'interest_rate' => 0.01,
            'surcharge_rate' => 0.01,
            'service_fee' => 0.005,
            'cbu_common' => 0.02,
            'imprest' => 0.01,
            'insurance' => 0.0192,
        ]);
        LoanType::create([
            'code' => 'KL',
            'name' => 'Kabuhayan Loan',
            'minimum_cbu' => 16667,
            'max_amount' => 50000,
            'interest_rate' => 0.015,
            'service_fee' => 0.005,
            'cbu_common' => 0.02,
            'imprest' => 0.01,
            'insurance' => 0.0192,
        ]);
        LoanType::create([
            'code' => 'SL',
            'name' => 'Special Loan',
            'interest_rate' => 0.01,
            'surcharge_rate' => 0.01,
            'service_fee' => 0.003,
            'cbu_common' => 0.02,
            'imprest' => 0.01,
            'insurance' => 0.0192,
        ]);
        LoanType::create([
            'code' => 'FNPL',
            'name' => 'Fly Now Pay Later Loan',
            'minimum_cbu' => 10000,
            'max_amount' => 30000,
            'interest_rate' => 0.01,
            'surcharge_rate' => 0.01,
            'service_fee' => 0.005,
            'cbu_common' => 0.02,
            'imprest' => 0.01,
            'insurance' => 0.0192,
        ]);
        LoanType::create([
            'code' => 'ACL',
            'name' => 'Additional Commodity Loan',
            'interest_rate' => 0.01,
            'surcharge_rate' => 0.01,
            'service_fee' => 0.005,
            'cbu_common' => 0.02,
            'imprest' => 0.01,
            'insurance' => 0.0192,
        ]);

        LoanType::create([
            'code' => 'LBP-ATM',
            'name' => 'LBP ATM CARD Loan',
            'minimum_cbu' => 117000,
            'max_amount' => 350000,
            'interest_rate' => 0.01,
            'surcharge_rate' => 0.01,
            'service_fee' => 0.005,
            'cbu_common' => 0.02,
            'imprest' => 0.01,
            'insurance' => 0.0192,
        ]);
        LoanType::create([
            'code' => 'LBP-RL',
            'name' => 'LBP-Regular Loan',
            'interest_rate' => 0.01,
            'surcharge_rate' => 0.01,
            'service_fee' => 0.005,
            'cbu_common' => 0.02,
            'imprest' => 0.01,
            'insurance' => 0.0192,
        ]);
        LoanType::create([
            'code' => 'RES-LBP ATM',
            'name' => 'Restructuring-LBP ATM',
            'minimum_cbu' => 117000,
            'max_amount' => 350000,
            'interest_rate' => 0.01,
            'surcharge_rate' => 0.01,
            'service_fee' => 0.005,
            'cbu_common' => 0.02,
            'imprest' => 0.01,
            'insurance' => 0.0192,
        ]);
        LoanType::create([
            'code' => 'RES-KL',
            'name' => 'Restructuring-KL',
            'interest_rate' => 0.01,
            'surcharge_rate' => 0.01,
            'service_fee' => 0.005,
            'cbu_common' => 0.02,
            'imprest' => 0.01,
            'insurance' => 0.0192,
        ]);
        LoanType::create([
            'code' => 'RES-PL',
            'name' => 'Restructuring-PL',
            'interest_rate' => 0.01,
            'surcharge_rate' => 0.01,
            'service_fee' => 0.005,
            'cbu_common' => 0.02,
            'imprest' => 0.01,
            'insurance' => 0.0192,
        ]);
        LoanType::create([
            'code' => 'RES-LBP CL',
            'name' => 'RES-LBP Commodity Loan',
            'minimum_cbu' => 50000,
            'interest_rate' => 0.01,
            'surcharge_rate' => 0.01,
            'service_fee' => 0.005,
            'cbu_common' => 0.02,
            'imprest' => 0.01,
            'insurance' => 0.0192,
        ]);
        LoanType::create([
            'code' => 'RES-RL',
            'name' => 'Restructuring-RL',
            'interest_rate' => 0.01,
            'surcharge_rate' => 0.01,
            'service_fee' => 0.005,
            'cbu_common' => 0.02,
            'imprest' => 0.01,
            'insurance' => 0.0192,
        ]);
    }
}
