<?php

namespace App\Oxytoxin;

use App\Models\LoanType;
use App\Models\Member;

class LoansProvider
{
    const LOAN_TERMS = [
        12 => 12,
        24 => 24,
        36 => 36,
        48 => 48,
        60 => 60,
        72 => 72,
        84 => 84,
    ];

    public static function computeInterest($amount, ?LoanType $loanType, $number_of_terms)
    {
        if (!$loanType || !$amount || !$number_of_terms)
            return 0;
        return round($amount * $loanType->interest_rate * $number_of_terms, 2);
    }

    public static function computeMonthlyPayment($amount, ?LoanType $loanType, $number_of_terms)
    {
        if (!$loanType || !$amount || !$number_of_terms)
            return 0;
        return round($amount * (1 + $loanType->interest_rate * $number_of_terms) / $number_of_terms, 2);
    }

    public static function computeDeductions(?LoanType $loanType, $gross_amount, ?Member $member): array
    {
        if (!$loanType)
            return [];
        $deductions = [
            [
                'name' => 'Service Fee',
                'amount' => round($loanType->service_fee * $gross_amount, 2),
                'readonly' => true,
                'code' => 'service_fee',
            ],
            [
                'name' => 'CBU-Common',
                'amount' => round($loanType->cbu_common * $gross_amount, 2),
                'readonly' => true,
                'code' => 'cbu_common',
            ],
            [
                'name' => 'Imprest Savings',
                'amount' => round($loanType->imprest * $gross_amount, 2),
                'readonly' => true,
                'code' => 'imprest',
            ],
            [
                'name' => 'Insurance-LOAN',
                'amount' => round($loanType->insurance * $gross_amount, 2),
                'readonly' => true,
                'code' => 'insurance',
            ],
        ];
        $existing = $member?->loans()->where('loan_type_id', $loanType->id)->where('outstanding_balance', '>', 0)->first();
        if ($existing) {
            $deductions[] = [
                'name' => 'Loan Buy-out',
                'amount' => $existing->outstanding_balance,
                'readonly' => true,
                'code' => 'buy_out',
                'loan_id' => $existing->id,
            ];
        }
        return $deductions;
    }
}
