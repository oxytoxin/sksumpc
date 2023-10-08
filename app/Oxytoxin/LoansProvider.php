<?php

namespace App\Oxytoxin;

use App\Models\LoanType;

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
        if (!$loanType)
            return 0;
        return round($amount * $loanType->interest * $number_of_terms, 2);
    }

    public static function computeMonthlyPayment($amount, ?LoanType $loanType, $number_of_terms)
    {
        if (!$loanType)
            return 0;
        return round($amount * (1 + $loanType->interest * $number_of_terms) / $number_of_terms, 2);
    }
}
