<?php

namespace App\Oxytoxin;

use App\Models\Loan;
use App\Models\LoanType;
use App\Models\Member;
use Carbon\Carbon;
use Carbon\CarbonImmutable;

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

    public static function getAccruableDays(Carbon|CarbonImmutable $start, Carbon|CarbonImmutable $end)
    {
        if ($start->month == $end->month && $start->year == $end->year) {
            $days_of_start_month = max($end->day - $start->day, 0);
            $days_of_end_month = 0;
        } else {
            $days_of_start_month = max(LoansProvider::DAYS_IN_MONTH - $start->day, 0);
            $days_of_end_month = min($end->day, LoansProvider::DAYS_IN_MONTH);
        }
        $days_of_months_between = max($start->diffInMonths($end) - 1, 0) * LoansProvider::DAYS_IN_MONTH;

        $total_days = $days_of_start_month + $days_of_months_between + $days_of_end_month;

        return $total_days;
    }

    public static function computeAccruedInterest(Loan $loan, $outstanding_balance, $days)
    {
        bcscale(10);
        return bcmul($loan->interest_rate, bcmul($outstanding_balance, bcdiv($days, LoansProvider::DAYS_IN_MONTH)));
    }

    public static function computeInterest($amount, ?LoanType $loanType, $number_of_terms, $transaction_date = null)
    {
        if (!$loanType || !$amount || !$number_of_terms) {
            return 0;
        }
        $loan = Loan::make([
            'interest_rate' => $loanType->interest_rate,
            'gross_amount' => $amount,
            'number_of_terms' => $number_of_terms,
            'transaction_date' => $transaction_date ?? today(),
        ]);

        $schedule = static::generateAmortizationSchedule($loan);

        return collect($schedule)->sum('interest');
    }

    public static function computeMonthlyPayment($amount, ?LoanType $loanType, $number_of_terms, $transaction_date = null)
    {
        if (!$loanType || !$amount || !$number_of_terms) {
            return 0;
        }

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
        $number_of_terms = $loan->number_of_terms;
        $interest_rate = $loan->interest_rate;
        $gross_amount = $loan->gross_amount;
        $o = 1 + $interest_rate;
        $p = pow($o, $number_of_terms);
        $q = $interest_rate * $p;
        $r = $p - 1;
        $s = $q / $r;
        $t = $gross_amount * $s;

        return round($t, 4);
    }

    public static function computeDeductions(?LoanType $loanType, $gross_amount, ?Member $member, $existing_loan_id = null): array
    {
        if (!$loanType) {
            return [];
        }
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
                'code' => 'cbu_amount',
            ],
            [
                'name' => 'Imprest Savings',
                'amount' => round($loanType->imprest * $gross_amount, 2),
                'readonly' => true,
                'code' => 'imprest_amount',
            ],
            [
                'name' => 'Insurance-LOAN',
                'amount' => round($loanType->insurance * $gross_amount, 2),
                'readonly' => false,
                'code' => 'insurance_amount',
            ],
        ];
        $existing = $member?->loans()->wherePosted(true)->where('loan_type_id', $loanType->id)->where('outstanding_balance', '>', 0)->whereNot('id', $existing_loan_id)->first();
        if ($existing) {
            $amortizations = $existing->loan_amortizations;
            $interest_paid = $amortizations->sum('amount_paid') - $amortizations->sum('principal_payment');
            $interest_remaining = $amortizations->sum('interest') - $interest_paid;
            $deductions[] = [
                'name' => 'Loan Buy-out Interest',
                'amount' => $interest_remaining,
                'readonly' => true,
                'code' => 'loan_buyout_interest',
                'loan_id' => $existing->id,
            ];

            $deductions[] = [
                'name' => 'Loan Buy-out Principal',
                'amount' => $existing->outstanding_balance,
                'readonly' => true,
                'code' => 'loan_buyout_principal',
                'loan_id' => $existing->id,
            ];
        }

        return $deductions;
    }

    public static function generateAmortizationSchedule(Loan $loan): array
    {
        $schedule = [];
        $outstanding_balance = $loan->gross_amount;
        $start = $loan->transaction_date ?? today();
        $amortization = LoansProvider::computeRegularAmortization($loan);
        bcscale(10);

        for ($i = 1; $i <= $loan->number_of_terms; $i++) {
            if ($start->day <= 10) {
                if ($i == 1) {
                    $days = LoansProvider::DAYS_IN_MONTH - $start->day;
                } else if ($i == $loan->number_of_terms) {
                    $days = LoansProvider::DAYS_IN_MONTH + $start->day;
                } else {
                    $days = LoansProvider::DAYS_IN_MONTH;
                }
                $date = $start->addMonthsNoOverflow($i - 1);
            } else {
                if ($i == 1) {
                    $days = (LoansProvider::DAYS_IN_MONTH * 2) - $start->day;
                } else if ($i == $loan->number_of_terms) {
                    $days = $start->day;
                } else {
                    $days = LoansProvider::DAYS_IN_MONTH;
                }
                $date = $start->addMonthsNoOverflow($i);
            }

            $interest = bcmul($loan->interest_rate, bcmul($outstanding_balance, bcdiv($days, LoansProvider::DAYS_IN_MONTH)));
            if ($i == $loan->number_of_terms) {
                $amortization = bcadd($outstanding_balance, $interest);
                $principal = $outstanding_balance;
            } else {
                $principal = bcsub($amortization, $interest);
            }

            $schedule[] = [
                'term' => $i,
                'date' => $date,
                'days' => $days,
                'amortization' => $amortization,
                'interest' => $interest,
                'principal' => $principal,
                'previous_balance' => round($outstanding_balance, 4),
            ];

            $outstanding_balance = round(bcsub($outstanding_balance, $principal), 4);
        }
        return $schedule;
    }
}
