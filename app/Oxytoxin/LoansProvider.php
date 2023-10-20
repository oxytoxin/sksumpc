<?php

namespace App\Oxytoxin;

use App\Models\Loan;
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

    const DAYS_IN_MONTH = 30;

    public static function computeInterest($amount, ?LoanType $loanType, $number_of_terms, $transaction_date)
    {
        if (!$loanType || !$amount || !$number_of_terms)
            return 0;
        // original
        //return round($amount * $loanType->interest_rate * $number_of_terms, 2);
        $loan = Loan::make([
            'interest_rate' => $loanType->interest_rate,
            'gross_amount' => $amount,
            'number_of_terms' => $number_of_terms,
            'transaction_date' => $transaction_date ?? today()
        ]);

        $schedule = static::generateAmortizationSchedule($loan);
        return collect($schedule)->sum('interest');
    }

    public static function computeMonthlyPayment($amount, ?LoanType $loanType, $number_of_terms, $transaction_date)
    {
        if (!$loanType || !$amount || !$number_of_terms)
            return 0;
        // original
        // return round($amount * (1 + $loanType->interest_rate * $number_of_terms) / $number_of_terms, 2);

        // from Excel
        $loan = Loan::make([
            'interest_rate' => $loanType->interest_rate,
            'gross_amount' => $amount,
            'number_of_terms' => $number_of_terms,
            'transaction_date' => $transaction_date ?? today(),
        ]);
        return static::computeRegularAmortization($loan);
    }

    public static function computeRegularAmortization(Loan $loan)
    {
        $pt = $loan->number_of_terms;
        $mi = $loan->interest_rate;
        $la = $loan->gross_amount;
        $o = 1 + $mi;
        $p = pow($o, $pt);
        $q = $mi * $p;
        $r = $p - 1;
        $s = $q / $r;
        $t = $la * $s;
        return round($t, 2);
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
                'readonly' => false,
                'code' => 'insurance',
            ],
        ];
        $existing = $member?->loans()->where('loan_type_id', $loanType->id)->where('outstanding_balance', '>', 0)->first();
        if ($existing) {
            $days = $existing->transaction_date->diffInDays(today());
            $interest = $existing->interest_rate * $existing->outstanding_balance * ($days / LoansProvider::DAYS_IN_MONTH);

            $deductions[] = [
                'name' => 'Loan Buy-out',
                'amount' => $interest + $existing->outstanding_balance,
                'readonly' => true,
                'code' => 'buy_out',
                'loan_id' => $existing->id,
            ];
        }
        return $deductions;
    }

    public static function generateAmortizationSchedule(Loan $loan): array
    {
        $schedule = [];
        $outstanding_balance = $loan->gross_amount;
        $start = $loan->transaction_date;
        $term = 1;
        $amortization = LoansProvider::computeRegularAmortization($loan);
        do {
            if ($term == 1) {
                $days = $start->diffInDays($start->addMonthNoOverflow()->endOfMonth()) + 1;
            } else if ($term == $loan->number_of_terms) {
                $days = $start->diffInDays($loan->transaction_date->addMonthsNoOverflow($loan->number_of_terms));
            } else {
                $days = 30;
            }

            $interest = $loan->interest_rate * $outstanding_balance * ($days / LoansProvider::DAYS_IN_MONTH);
            if ($term == $loan->number_of_terms) {
                $interest = $loan->interest_rate * $outstanding_balance * (LoansProvider::DAYS_IN_MONTH / LoansProvider::DAYS_IN_MONTH);
                $amortization = $outstanding_balance + $interest;
            }

            $principal = $amortization - $interest;

            $schedule[] = [
                'term' => $term,
                'date' => $start->addMonthNoOverflow()->format('F Y'),
                'days' => $days,
                'amortization' => $amortization,
                'interest' => $interest,
                'principal' => $principal,
                'previous_balance' => $outstanding_balance,
                'outstanding_balance' => $outstanding_balance - $principal,
            ];

            $outstanding_balance -= $principal;
            $start = $start->addMonthNoOverflow()->endOfMonth();
            $term++;
        } while ($term <= $loan->number_of_terms);
        return $schedule;
    }
}
