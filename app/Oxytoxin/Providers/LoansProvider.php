<?php

namespace App\Oxytoxin\Providers;

use App\Enums\LoanTypes;
use App\Models\Account;
use App\Models\Loan;
use App\Models\LoanType;
use App\Models\Member;

class LoansProvider
{
    const LOAN_TERMS = [
        1 => 1,
        3 => 3,
        4 => 4,
        6 => 6,
        12 => 12,
        24 => 24,
        36 => 36,
        48 => 48,
        60 => 60,
        72 => 72,
        84 => 84,
        96 => 96,
    ];

    const DAYS_IN_MONTH = 30;

    public static function getAccruableDays($start, $end)
    {
        //same month, same year just return difference of days
        $days = 0;
        if ($start->month == $end->month && $start->year == $end->year) {
            $days = max($end->day - $start->day, 0);
        } else {
            $days += max(LoansProvider::DAYS_IN_MONTH - $start->day, 0);
            $start = $start->addMonthNoOverflow();
            while (!($start->month == $end->month && $start->year == $end->year)) {
                $days += LoansProvider::DAYS_IN_MONTH;
                $start = $start->addMonthNoOverflow();
            }
            $days += min($end->day, LoansProvider::DAYS_IN_MONTH);
        }
        return $days;
    }

    public static function computeAccruedInterest(Loan $loan, $outstanding_balance, $days)
    {
        bcscale(10);
        if ($loan->loan_type_id == LoanTypes::SPECIAL_LOAN->value)
            return 0;
        return bcmul($loan->interest_rate, bcmul($outstanding_balance, bcdiv($days, LoansProvider::DAYS_IN_MONTH)));
    }

    public static function computeInterest($amount, ?LoanType $loanType, $number_of_terms, $transaction_date = null)
    {
        if (! $loanType || ! $amount || ! $number_of_terms) {
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
        if (! $loanType || ! $amount || ! $number_of_terms) {
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

    public static function getDisclosureSheetItems(?LoanType $loanType, $gross_amount, ?Member $member, $existing_loan_id = null): array
    {
        if (! $loanType) {
            return [];
        }
        $items = [];

        $items[] = [
            'member_id' => null,
            'account_id' => Account::getLoanReceivable($loanType)->id,
            'credit' => null,
            'debit' => $gross_amount,
            'readonly' => true,
            'code' => 'gross_amount',
        ];

        $items[] = [
            'member_id' => null,
            'account_id' => Account::getServiceFeeLoans()->id,
            'credit' => round($loanType->service_fee * $gross_amount, 4),
            'debit' => null,
            'readonly' => true,
            'code' => 'service_fee',
        ];

        $items[] = [
            'member_id' => null,
            'account_id' => Account::getFamilyInsurance()->id,
            'credit' => 190,
            'debit' => null,
            'readonly' => true,
            'code' => 'service_fee',
        ];

        if ($loanType->id != 2) {
            $items[] = [
                'member_id' => $member->id,
                'account_id' => $member->capital_subscription_account->id,
                'credit' => round($loanType->cbu_common * $gross_amount, 4),
                'debit' => null,
                'readonly' => true,
                'code' => 'cbu_amount',
            ];
            $items[] = [
                'member_id' => $member->id,
                'account_id' => $member->imprest_account->id,
                'credit' => round($loanType->imprest * $gross_amount, 4),
                'debit' => null,
                'readonly' => true,
                'code' => 'imprest_amount',
            ];
        }
        $items[] = [
            'member_id' => null,
            'account_id' => Account::getLoanInsurance()->id,
            'credit' => round($loanType->insurance * $gross_amount, 4),
            'debit' => null,
            'readonly' => true,
            'code' => 'insurance_amount',
        ];
        $existing = $member?->loans()->wherePosted(true)->where('loan_type_id', $loanType->id)->where('outstanding_balance', '>', 0)->whereNot('id', $existing_loan_id)->first();
        if ($existing) {
            $start = $existing->last_payment?->transaction_date ?? $existing->transaction_date;
            $end = (config('app.transaction_date') ?? today());
            $total_days = LoansProvider::getAccruableDays($start, $end);
            $interest_remaining = LoansProvider::computeAccruedInterest($existing, $existing->outstanding_balance, $total_days);
            $items[] = [
                'member_id' => null,
                'account_id' => $existing->loan_account_id,
                'credit' => $existing->outstanding_balance + $interest_remaining,
                'debit' => null,
                'readonly' => true,
                'code' => 'loan_buyout',
                'loan_id' => $existing->id,
            ];
        }
        $items[] = [
            'member_id' => null,
            'account_id' => Account::getCashInBankGF()->id,
            'credit' => round($gross_amount - collect($items)->sum('credit'), 4),
            'debit' => null,
            'readonly' => true,
            'code' => 'net_amount',
        ];
        return $items;
    }

    public static function computeDeductions(?LoanType $loanType, $gross_amount, ?Member $member, $existing_loan_id = null): array
    {
        if (! $loanType) {
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
            $start = $existing->last_payment?->transaction_date ?? $existing->transaction_date;
            $end = (config('app.transaction_date') ?? today());
            $total_days = LoansProvider::getAccruableDays($start, $end);
            $interest_remaining = LoansProvider::computeAccruedInterest($existing, $existing->outstanding_balance, $total_days);
            $deductions[] = [
                'name' => 'Loan Buy-out',
                'amount' => $existing->outstanding_balance + $interest_remaining,
                'readonly' => true,
                'code' => 'loan_buyout',
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
                } else {
                    $days = LoansProvider::DAYS_IN_MONTH;
                }
                $date = $start->addMonthsNoOverflow($i - 1);
            } else {
                if ($i == 1) {
                    $days = LoansProvider::DAYS_IN_MONTH +  max(LoansProvider::DAYS_IN_MONTH - $start->day, 0);
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

            $outstanding_balance = round(bcsub($outstanding_balance, $principal), 6);
        }

        return $schedule;
    }
}
