<?php

namespace App\Oxytoxin\Providers;

use App\Models\Account;
use Cache;
use Carbon\Carbon;
use Carbon\CarbonImmutable;
use Illuminate\Support\Facades\DB;

class TrialBalanceProvider
{
    public static function getYearlyTrialBalance(int $year)
    {
        if (env('APP_ENV') === 'production') {
            Cache::forget('trial_balance_'.$year);
        }

        return Cache::remember('trial_balance_'.$year, 3600, function () use ($year) {
            return self::getYearlyAccountSummary($year);
        });
    }

    public static function getComparativeTrialBalance(CarbonImmutable $from, CarbonImmutable $to)
    {
        return Account::withQueryConstraint(function ($query) use ($from, $to) {
            $balance_forwarded_date = $from->subMonthNoOverflow();
            $ending_date = $balance_forwarded_date->endOfMonth();
            $query
                ->withCount(['children' => fn ($q) => $q->whereNull('member_id')])
                ->withSum(['recursiveCrjTransactions as 0_month_crj_debit' => fn ($query) => $query->whereMonth('transaction_date', $balance_forwarded_date->month)->whereYear('transaction_date', $balance_forwarded_date->year)], 'debit')
                ->withSum(['recursiveCrjTransactions as 0_month_crj_credit' => fn ($query) => $query->whereMonth('transaction_date', $balance_forwarded_date->month)->whereYear('transaction_date', $balance_forwarded_date->year)], 'credit')
                ->withSum(['recursiveCdjTransactions as 0_month_cdj_debit' => fn ($query) => $query->whereMonth('transaction_date', $balance_forwarded_date->month)->whereYear('transaction_date', $balance_forwarded_date->year)], 'debit')
                ->withSum(['recursiveCdjTransactions as 0_month_cdj_credit' => fn ($query) => $query->whereMonth('transaction_date', $balance_forwarded_date->month)->whereYear('transaction_date', $balance_forwarded_date->year)], 'credit')
                ->withSum(['recursiveJevTransactions as 0_month_jev_debit' => fn ($query) => $query->whereMonth('transaction_date', $balance_forwarded_date->month)->whereYear('transaction_date', $balance_forwarded_date->year)], 'debit')
                ->withSum(['recursiveJevTransactions as 0_month_jev_credit' => fn ($query) => $query->whereMonth('transaction_date', $balance_forwarded_date->month)->whereYear('transaction_date', $balance_forwarded_date->year)], 'credit')
                ->withSum(['recursiveCrjTransactions as 0_ending_crj_debit' => fn ($query) => $query->whereDate('transaction_date', '<=', $ending_date)], 'debit')
                ->withSum(['recursiveCrjTransactions as 0_ending_crj_credit' => fn ($query) => $query->whereDate('transaction_date', '<=', $ending_date)], 'credit')
                ->withSum(['recursiveCdjTransactions as 0_ending_cdj_debit' => fn ($query) => $query->whereDate('transaction_date', '<=', $ending_date)], 'debit')
                ->withSum(['recursiveCdjTransactions as 0_ending_cdj_credit' => fn ($query) => $query->whereDate('transaction_date', '<=', $ending_date)], 'credit')
                ->withSum(['recursiveJevTransactions as 0_ending_jev_debit' => fn ($query) => $query->whereDate('transaction_date', '<=', $ending_date)], 'debit')
                ->withSum(['recursiveJevTransactions as 0_ending_jev_credit' => fn ($query) => $query->whereDate('transaction_date', '<=', $ending_date)], 'credit');
            foreach ([$from, $to] as $key => $date) {
                $k = $key + 1;
                $query
                    ->withSum(["recursiveCrjTransactions as {$k}_month_crj_debit" => fn ($query) => $query->whereDate('transaction_date', '<=', $date->endOfDay())->whereDate('transaction_date', '>=', $date->startOfMonth())], 'debit')
                    ->withSum(["recursiveCrjTransactions as {$k}_month_crj_credit" => fn ($query) => $query->whereDate('transaction_date', '<=', $date->endOfDay())->whereDate('transaction_date', '>=', $date->startOfMonth())], 'credit')
                    ->withSum(["recursiveCdjTransactions as {$k}_month_cdj_debit" => fn ($query) => $query->whereDate('transaction_date', '<=', $date->endOfDay())->whereDate('transaction_date', '>=', $date->startOfMonth())], 'debit')
                    ->withSum(["recursiveCdjTransactions as {$k}_month_cdj_credit" => fn ($query) => $query->whereDate('transaction_date', '<=', $date->endOfDay())->whereDate('transaction_date', '>=', $date->startOfMonth())], 'credit')
                    ->withSum(["recursiveJevTransactions as {$k}_month_jev_debit" => fn ($query) => $query->whereDate('transaction_date', '<=', $date->endOfDay())->whereDate('transaction_date', '>=', $date->startOfMonth())], 'debit')
                    ->withSum(["recursiveJevTransactions as {$k}_month_jev_credit" => fn ($query) => $query->whereDate('transaction_date', '<=', $date->endOfDay())->whereDate('transaction_date', '>=', $date->startOfMonth())], 'credit')
                    ->withSum(["recursiveCrjTransactions as {$k}_ending_crj_debit" => fn ($query) => $query->whereDate('transaction_date', '<=', $date->endOfDay())], 'debit')
                    ->withSum(["recursiveCrjTransactions as {$k}_ending_crj_credit" => fn ($query) => $query->whereDate('transaction_date', '<=', $date->endOfDay())], 'credit')
                    ->withSum(["recursiveCdjTransactions as {$k}_ending_cdj_debit" => fn ($query) => $query->whereDate('transaction_date', '<=', $date->endOfDay())], 'debit')
                    ->withSum(["recursiveCdjTransactions as {$k}_ending_cdj_credit" => fn ($query) => $query->whereDate('transaction_date', '<=', $date->endOfDay())], 'credit')
                    ->withSum(["recursiveJevTransactions as {$k}_ending_jev_debit" => fn ($query) => $query->whereDate('transaction_date', '<=', $date->endOfDay())], 'debit')
                    ->withSum(["recursiveJevTransactions as {$k}_ending_jev_credit" => fn ($query) => $query->whereDate('transaction_date', '<=', $date->endOfDay())], 'credit');
            }
            $query
                ->whereNull('accounts.member_id');
        }, function () {
            $addSelectStatement = 'laravel_cte.*,';
            foreach ([0, 1, 2] as $key) {
                $addSelectStatement .= "
                    (
                        coalesce({$key}_month_crj_debit, 0) + 
                        coalesce({$key}_month_cdj_debit, 0) + 
                        coalesce({$key}_month_jev_debit, 0)
                    ) as {$key}_total_debit, 
                    (
                        coalesce({$key}_month_crj_credit, 0) +
                        coalesce({$key}_month_cdj_credit, 0) + 
                        coalesce({$key}_month_jev_credit, 0)
                    ) as {$key}_total_credit, 
                        case when debit_operator > 0 then (
                            (
                                coalesce({$key}_ending_crj_debit, 0) + 
                                coalesce({$key}_ending_cdj_debit, 0) + 
                                coalesce({$key}_ending_jev_debit, 0)
                            ) * debit_operator +
                            (
                                coalesce({$key}_ending_crj_credit, 0) +
                                coalesce({$key}_ending_cdj_credit, 0) + 
                                coalesce({$key}_ending_jev_credit, 0)
                            ) * credit_operator
                        )
                        else 0
                        end    
                        as {$key}_ending_balance_debit,
                        case when credit_operator > 0 then (
                            (
                                coalesce({$key}_ending_crj_debit, 0) + 
                                coalesce({$key}_ending_cdj_debit, 0) + 
                                coalesce({$key}_ending_jev_debit, 0)
                            ) * debit_operator +
                            (
                                coalesce({$key}_ending_crj_credit, 0) +
                                coalesce({$key}_ending_cdj_credit, 0) + 
                                coalesce({$key}_ending_jev_credit, 0)
                            ) * credit_operator
                        ) 
                        else 0
                        end    
                        as {$key}_ending_balance_credit,
                ";
            }
            $addSelectStatement .= 'account_types.debit_operator, account_types.credit_operator';

            return Account::tree()
                ->orderBy('sort')
                ->join('account_types', 'account_type_id', 'account_types.id')
                ->addSelect(DB::raw($addSelectStatement))
                ->get();
        })->toTree();
    }

    public static function getSingleTrialBalance(CarbonImmutable $date)
    {

        return Account::withQueryConstraint(function ($query) use ($date) {
            $ending_date = $date->endOfMonth();
            $query
                ->withCount(['children' => fn ($q) => $q->whereNull('member_id')])
                ->withSum(['recursiveCrjTransactions as month_crj_debit' => fn ($query) => $query->whereMonth('transaction_date', $date->month)->whereYear('transaction_date', $date->year)], 'debit')
                ->withSum(['recursiveCrjTransactions as month_crj_credit' => fn ($query) => $query->whereMonth('transaction_date', $date->month)->whereYear('transaction_date', $date->year)], 'credit')
                ->withSum(['recursiveCdjTransactions as month_cdj_debit' => fn ($query) => $query->whereMonth('transaction_date', $date->month)->whereYear('transaction_date', $date->year)], 'debit')
                ->withSum(['recursiveCdjTransactions as month_cdj_credit' => fn ($query) => $query->whereMonth('transaction_date', $date->month)->whereYear('transaction_date', $date->year)], 'credit')
                ->withSum(['recursiveJevTransactions as month_jev_debit' => fn ($query) => $query->whereMonth('transaction_date', $date->month)->whereYear('transaction_date', $date->year)], 'debit')
                ->withSum(['recursiveJevTransactions as month_jev_credit' => fn ($query) => $query->whereMonth('transaction_date', $date->month)->whereYear('transaction_date', $date->year)], 'credit')
                ->withSum(['recursiveCrjTransactions as ending_crj_debit' => fn ($query) => $query->whereDate('transaction_date', '<=', $ending_date)], 'debit')
                ->withSum(['recursiveCrjTransactions as ending_crj_credit' => fn ($query) => $query->whereDate('transaction_date', '<=', $ending_date)], 'credit')
                ->withSum(['recursiveCdjTransactions as ending_cdj_debit' => fn ($query) => $query->whereDate('transaction_date', '<=', $ending_date)], 'debit')
                ->withSum(['recursiveCdjTransactions as ending_cdj_credit' => fn ($query) => $query->whereDate('transaction_date', '<=', $ending_date)], 'credit')
                ->withSum(['recursiveJevTransactions as ending_jev_debit' => fn ($query) => $query->whereDate('transaction_date', '<=', $ending_date)], 'debit')
                ->withSum(['recursiveJevTransactions as ending_jev_credit' => fn ($query) => $query->whereDate('transaction_date', '<=', $ending_date)], 'credit')
                ->whereNull('accounts.member_id');
        }, function () {
            $addSelectStatement = 'laravel_cte.*,';
            $addSelectStatement .= '
                    (
                        coalesce(month_crj_debit, 0) + 
                        coalesce(month_cdj_debit, 0) + 
                        coalesce(month_jev_debit, 0)
                    ) as total_debit, 
                    (
                        coalesce(month_crj_credit, 0) +
                        coalesce(month_cdj_credit, 0) + 
                        coalesce(month_jev_credit, 0)
                    ) as total_credit, 
                        case when debit_operator > 0 then (
                            (
                                coalesce(ending_crj_debit, 0) + 
                                coalesce(ending_cdj_debit, 0) + 
                                coalesce(ending_jev_debit, 0)
                            ) * debit_operator +
                            (
                                coalesce(ending_crj_credit, 0) +
                                coalesce(ending_cdj_credit, 0) + 
                                coalesce(ending_jev_credit, 0)
                            ) * credit_operator
                        )
                        else 0
                        end    
                        as ending_balance_debit,
                        case when credit_operator > 0 then (
                            (
                                coalesce(ending_crj_debit, 0) + 
                                coalesce(ending_cdj_debit, 0) + 
                                coalesce(ending_jev_debit, 0)
                            ) * debit_operator +
                            (
                                coalesce(ending_crj_credit, 0) +
                                coalesce(ending_cdj_credit, 0) + 
                                coalesce(ending_jev_credit, 0)
                            ) * credit_operator
                        ) 
                        else 0
                        end    
                        as ending_balance_credit,
                ';
            $addSelectStatement .= 'account_types.debit_operator, account_types.credit_operator';

            return Account::tree()
                ->orderBy('sort')
                ->join('account_types', 'account_type_id', 'account_types.id')
                ->addSelect(DB::raw($addSelectStatement))
                ->get();
        })->toTree();
    }

    public static function getYearlyAccountSummary($year)
    {
        return Account::withQueryConstraint(function ($query) use ($year) {
            $balance_forwarded_date = CarbonImmutable::create(year: $year)->subYearNoOverflow()->endOfYear();
            $ending_date = $balance_forwarded_date->endOfMonth();
            $query
                ->withCount(['children' => fn ($q) => $q->whereNull('member_id')])
                ->withSum(['recursiveCrjTransactions as 0_month_crj_debit' => fn ($query) => $query->whereMonth('transaction_date', $balance_forwarded_date->month)->whereYear('transaction_date', $balance_forwarded_date->year)], 'debit')
                ->withSum(['recursiveCrjTransactions as 0_month_crj_credit' => fn ($query) => $query->whereMonth('transaction_date', $balance_forwarded_date->month)->whereYear('transaction_date', $balance_forwarded_date->year)], 'credit')
                ->withSum(['recursiveCdjTransactions as 0_month_cdj_debit' => fn ($query) => $query->whereMonth('transaction_date', $balance_forwarded_date->month)->whereYear('transaction_date', $balance_forwarded_date->year)], 'debit')
                ->withSum(['recursiveCdjTransactions as 0_month_cdj_credit' => fn ($query) => $query->whereMonth('transaction_date', $balance_forwarded_date->month)->whereYear('transaction_date', $balance_forwarded_date->year)], 'credit')
                ->withSum(['recursiveJevTransactions as 0_month_jev_debit' => fn ($query) => $query->whereMonth('transaction_date', $balance_forwarded_date->month)->whereYear('transaction_date', $balance_forwarded_date->year)], 'debit')
                ->withSum(['recursiveJevTransactions as 0_month_jev_credit' => fn ($query) => $query->whereMonth('transaction_date', $balance_forwarded_date->month)->whereYear('transaction_date', $balance_forwarded_date->year)], 'credit')
                ->withSum(['recursiveCrjTransactions as 0_ending_crj_debit' => fn ($query) => $query->whereDate('transaction_date', '<=', $ending_date)], 'debit')
                ->withSum(['recursiveCrjTransactions as 0_ending_crj_credit' => fn ($query) => $query->whereDate('transaction_date', '<=', $ending_date)], 'credit')
                ->withSum(['recursiveCdjTransactions as 0_ending_cdj_debit' => fn ($query) => $query->whereDate('transaction_date', '<=', $ending_date)], 'debit')
                ->withSum(['recursiveCdjTransactions as 0_ending_cdj_credit' => fn ($query) => $query->whereDate('transaction_date', '<=', $ending_date)], 'credit')
                ->withSum(['recursiveJevTransactions as 0_ending_jev_debit' => fn ($query) => $query->whereDate('transaction_date', '<=', $ending_date)], 'debit')
                ->withSum(['recursiveJevTransactions as 0_ending_jev_credit' => fn ($query) => $query->whereDate('transaction_date', '<=', $ending_date)], 'credit');
            for ($m = 1; $m <= 12; $m++) {
                $ending_date = Carbon::create(month: $m, year: $year)->endOfMonth();
                $query
                    ->withSum(["recursiveCrjTransactions as {$m}_month_crj_debit" => fn ($query) => $query->whereMonth('transaction_date', $m)->whereYear('transaction_date', $year)], 'debit')
                    ->withSum(["recursiveCrjTransactions as {$m}_month_crj_credit" => fn ($query) => $query->whereMonth('transaction_date', $m)->whereYear('transaction_date', $year)], 'credit')
                    ->withSum(["recursiveCdjTransactions as {$m}_month_cdj_debit" => fn ($query) => $query->whereMonth('transaction_date', $m)->whereYear('transaction_date', $year)], 'debit')
                    ->withSum(["recursiveCdjTransactions as {$m}_month_cdj_credit" => fn ($query) => $query->whereMonth('transaction_date', $m)->whereYear('transaction_date', $year)], 'credit')
                    ->withSum(["recursiveJevTransactions as {$m}_month_jev_debit" => fn ($query) => $query->whereMonth('transaction_date', $m)->whereYear('transaction_date', $year)], 'debit')
                    ->withSum(["recursiveJevTransactions as {$m}_month_jev_credit" => fn ($query) => $query->whereMonth('transaction_date', $m)->whereYear('transaction_date', $year)], 'credit')
                    ->withSum(["recursiveCrjTransactions as {$m}_ending_crj_debit" => fn ($query) => $query->whereDate('transaction_date', '<=', $ending_date)], 'debit')
                    ->withSum(["recursiveCrjTransactions as {$m}_ending_crj_credit" => fn ($query) => $query->whereDate('transaction_date', '<=', $ending_date)], 'credit')
                    ->withSum(["recursiveCdjTransactions as {$m}_ending_cdj_debit" => fn ($query) => $query->whereDate('transaction_date', '<=', $ending_date)], 'debit')
                    ->withSum(["recursiveCdjTransactions as {$m}_ending_cdj_credit" => fn ($query) => $query->whereDate('transaction_date', '<=', $ending_date)], 'credit')
                    ->withSum(["recursiveJevTransactions as {$m}_ending_jev_debit" => fn ($query) => $query->whereDate('transaction_date', '<=', $ending_date)], 'debit')
                    ->withSum(["recursiveJevTransactions as {$m}_ending_jev_credit" => fn ($query) => $query->whereDate('transaction_date', '<=', $ending_date)], 'credit');
            }
            $query
                ->whereNull('accounts.member_id');
        }, function () {
            $addSelectStatement = 'laravel_cte.*,';
            for ($m = 0; $m <= 12; $m++) {
                $addSelectStatement .= "
                    (
                        coalesce({$m}_month_crj_debit, 0) + 
                        coalesce({$m}_month_cdj_debit, 0) + 
                        coalesce({$m}_month_jev_debit, 0)
                    ) as {$m}_total_debit, 
                    (
                        coalesce({$m}_month_crj_credit, 0) +
                        coalesce({$m}_month_cdj_credit, 0) + 
                        coalesce({$m}_month_jev_credit, 0)
                    ) as {$m}_total_credit, 
                        case when debit_operator > 0 then (
                            (
                                coalesce({$m}_ending_crj_debit, 0) + 
                                coalesce({$m}_ending_cdj_debit, 0) + 
                                coalesce({$m}_ending_jev_debit, 0)
                            ) * debit_operator +
                            (
                                coalesce({$m}_ending_crj_credit, 0) +
                                coalesce({$m}_ending_cdj_credit, 0) + 
                                coalesce({$m}_ending_jev_credit, 0)
                            ) * credit_operator
                        )
                        else 0
                        end    
                        as {$m}_ending_balance_debit,
                        case when credit_operator > 0 then (
                            (
                                coalesce({$m}_ending_crj_debit, 0) + 
                                coalesce({$m}_ending_cdj_debit, 0) + 
                                coalesce({$m}_ending_jev_debit, 0)
                            ) * debit_operator +
                            (
                                coalesce({$m}_ending_crj_credit, 0) +
                                coalesce({$m}_ending_cdj_credit, 0) + 
                                coalesce({$m}_ending_jev_credit, 0)
                            ) * credit_operator
                        ) 
                        else 0
                        end    
                        as {$m}_ending_balance_credit,
                ";
            }
            $addSelectStatement .= 'account_types.debit_operator, account_types.credit_operator';

            return Account::tree()
                ->orderBy('sort')
                ->join('account_types', 'account_type_id', 'account_types.id')
                ->addSelect(DB::raw($addSelectStatement))
                ->get();
        })->toTree();
    }
}
