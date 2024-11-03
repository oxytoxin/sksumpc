<?php

namespace App\Oxytoxin\Providers;

use Carbon\Carbon;
use App\Models\Account;
use Illuminate\Support\Facades\DB;
use App\Models\BalanceForwardedEntry;
use Cache;
use Carbon\CarbonImmutable;

class TrialBalanceProvider
{
    public static function getTrialBalance($year)
    {
        Cache::forget('trial_balance');
        return Cache::remember('trial_balance', 3600, function () use ($year) {
            return self::getYearlyAccountSummary($year);
        });
    }

    public static function getYearlyAccountSummary($year)
    {
        return Account::withQueryConstraint(function ($query) use ($year) {
            $balance_forwarded_date = CarbonImmutable::create(year: $year)->subYearNoOverflow()->endOfYear();
            $ending_date = $balance_forwarded_date->endOfMonth();
            $query
                ->withCount(['children' => fn($q) => $q->whereNull('member_id')])
                ->withSum(["recursiveCrjTransactions as 0_month_crj_debit" => fn($query) => $query->whereMonth('transaction_date', $balance_forwarded_date->month)->whereYear('transaction_date', $balance_forwarded_date->year)], 'debit')
                ->withSum(["recursiveCrjTransactions as 0_month_crj_credit" => fn($query) => $query->whereMonth('transaction_date', $balance_forwarded_date->month)->whereYear('transaction_date', $balance_forwarded_date->year)], 'credit')
                ->withSum(["recursiveCdjTransactions as 0_month_cdj_debit" => fn($query) => $query->whereMonth('transaction_date', $balance_forwarded_date->month)->whereYear('transaction_date', $balance_forwarded_date->year)], 'debit')
                ->withSum(["recursiveCdjTransactions as 0_month_cdj_credit" => fn($query) => $query->whereMonth('transaction_date', $balance_forwarded_date->month)->whereYear('transaction_date', $balance_forwarded_date->year)], 'credit')
                ->withSum(["recursiveJevTransactions as 0_month_jev_debit" => fn($query) => $query->whereMonth('transaction_date', $balance_forwarded_date->month)->whereYear('transaction_date', $balance_forwarded_date->year)], 'debit')
                ->withSum(["recursiveJevTransactions as 0_month_jev_credit" => fn($query) => $query->whereMonth('transaction_date', $balance_forwarded_date->month)->whereYear('transaction_date', $balance_forwarded_date->year)], 'credit')
                ->withSum(["recursiveCrjTransactions as 0_ending_crj_debit" => fn($query) => $query->whereDate('transaction_date', '<=', $ending_date)], 'debit')
                ->withSum(["recursiveCrjTransactions as 0_ending_crj_credit" => fn($query) => $query->whereDate('transaction_date', '<=', $ending_date)], 'credit')
                ->withSum(["recursiveCdjTransactions as 0_ending_cdj_debit" => fn($query) => $query->whereDate('transaction_date', '<=', $ending_date)], 'debit')
                ->withSum(["recursiveCdjTransactions as 0_ending_cdj_credit" => fn($query) => $query->whereDate('transaction_date', '<=', $ending_date)], 'credit')
                ->withSum(["recursiveJevTransactions as 0_ending_jev_debit" => fn($query) => $query->whereDate('transaction_date', '<=', $ending_date)], 'debit')
                ->withSum(["recursiveJevTransactions as 0_ending_jev_credit" => fn($query) => $query->whereDate('transaction_date', '<=', $ending_date)], 'credit')
            ;
            for ($m = 1; $m <= 12; $m++) {
                $ending_date = Carbon::create(month: $m, year: $year)->endOfMonth();
                $query
                    ->withSum(["recursiveCrjTransactions as {$m}_month_crj_debit" => fn($query) => $query->whereMonth('transaction_date', $m)->whereYear('transaction_date', $year)], 'debit')
                    ->withSum(["recursiveCrjTransactions as {$m}_month_crj_credit" => fn($query) => $query->whereMonth('transaction_date', $m)->whereYear('transaction_date', $year)], 'credit')
                    ->withSum(["recursiveCdjTransactions as {$m}_month_cdj_debit" => fn($query) => $query->whereMonth('transaction_date', $m)->whereYear('transaction_date', $year)], 'debit')
                    ->withSum(["recursiveCdjTransactions as {$m}_month_cdj_credit" => fn($query) => $query->whereMonth('transaction_date', $m)->whereYear('transaction_date', $year)], 'credit')
                    ->withSum(["recursiveJevTransactions as {$m}_month_jev_debit" => fn($query) => $query->whereMonth('transaction_date', $m)->whereYear('transaction_date', $year)], 'debit')
                    ->withSum(["recursiveJevTransactions as {$m}_month_jev_credit" => fn($query) => $query->whereMonth('transaction_date', $m)->whereYear('transaction_date', $year)], 'credit')
                    ->withSum(["recursiveCrjTransactions as {$m}_ending_crj_debit" => fn($query) => $query->whereDate('transaction_date', '<=', $ending_date)], 'debit')
                    ->withSum(["recursiveCrjTransactions as {$m}_ending_crj_credit" => fn($query) => $query->whereDate('transaction_date', '<=', $ending_date)], 'credit')
                    ->withSum(["recursiveCdjTransactions as {$m}_ending_cdj_debit" => fn($query) => $query->whereDate('transaction_date', '<=', $ending_date)], 'debit')
                    ->withSum(["recursiveCdjTransactions as {$m}_ending_cdj_credit" => fn($query) => $query->whereDate('transaction_date', '<=', $ending_date)], 'credit')
                    ->withSum(["recursiveJevTransactions as {$m}_ending_jev_debit" => fn($query) => $query->whereDate('transaction_date', '<=', $ending_date)], 'debit')
                    ->withSum(["recursiveJevTransactions as {$m}_ending_jev_credit" => fn($query) => $query->whereDate('transaction_date', '<=', $ending_date)], 'credit')
                ;
            }
            $query
                ->whereNull('accounts.member_id');
        }, function () use ($year) {
            $addSelectStatement = "laravel_cte.*,";
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
            $addSelectStatement .= "account_types.debit_operator, account_types.credit_operator";

            return Account::tree()
                ->orderBy('sort')
                ->join('account_types', 'account_type_id', 'account_types.id')
                ->addSelect(DB::raw($addSelectStatement))
                ->get();
        })->toTree();
    }
}
