<?php

namespace App\Oxytoxin\Providers;

use App\Models\Account;
use App\Models\BalanceForwardedEntry;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;

class FinancialStatementProvider
{
    public static function getDailyAccountsSummary($date)
    {
        return Account::withQueryConstraint(function ($query) use ($date) {
            $query
                ->withCount(['children' => fn ($q) => $q->whereNull('member_id')])
                ->withSum(['recursiveCrjTransactions as total_crj_debit' => fn ($query) => $query->whereDate('transaction_date', $date)], 'debit')
                ->withSum(['recursiveCrjTransactions as total_crj_credit' => fn ($query) => $query->whereDate('transaction_date', $date)], 'credit')
                ->withSum(['recursiveCdjTransactions as total_cdj_debit' => fn ($query) => $query->whereDate('transaction_date', $date)], 'debit')
                ->withSum(['recursiveCdjTransactions as total_cdj_credit' => fn ($query) => $query->whereDate('transaction_date', $date)], 'credit')
                ->withSum(['recursiveJevTransactions as total_jev_debit' => fn ($query) => $query->whereDate('transaction_date', $date)], 'debit')
                ->withSum(['recursiveJevTransactions as total_jev_credit' => fn ($query) => $query->whereDate('transaction_date', $date)], 'credit')
                ->whereNull('accounts.member_id');
        }, function () use ($date) {
            $balance_forwarded_date = Carbon::create($date)->subMonthNoOverflow();
            $joinSub = BalanceForwardedEntry::whereHas(
                'balance_forwarded_summary',
                fn ($q) => $q
                    ->whereMonth('generated_date', $balance_forwarded_date->month)
                    ->whereYear('generated_date', $balance_forwarded_date->year)
            )->groupByRaw('account_id, balance_forwarded_summary_id')->selectRaw('balance_forwarded_entries.balance_forwarded_summary_id, balance_forwarded_entries.account_id, sum(debit) as debit, sum(credit) as credit');

            return Account::tree()
                ->orderBy('sort')
                ->leftJoinSub($joinSub, 'balance_forwarded_entries', function ($join) {
                    $join->on('laravel_cte.id', '=', 'balance_forwarded_entries.account_id');
                })
                ->join('account_types', 'account_type_id', 'account_types.id')
                ->addSelect(DB::raw(
                    'balance_forwarded_entries.balance_forwarded_summary_id as balance_forwarded_summary_id,
                     balance_forwarded_entries.debit as balance_forwarded_debit, 
                     balance_forwarded_entries.credit as balance_forwarded_credit, 
                     laravel_cte.*, 
                     account_types.debit_operator, 
                     account_types.credit_operator, 
                    (
                        coalesce(total_crj_debit, 0) + 
                        coalesce(total_cdj_debit, 0) + 
                        coalesce(total_jev_debit, 0)
                    ) as total_debit, 
                    (
                        coalesce(total_crj_credit, 0) +
                        coalesce(total_cdj_credit, 0) + 
                        coalesce(total_jev_credit, 0)
                    ) as total_credit, 
                        (
                            (
                                coalesce( balance_forwarded_entries.debit, 0) + 
                                coalesce(total_crj_debit, 0) + 
                                coalesce(total_cdj_debit, 0) + 
                                coalesce(total_jev_debit, 0)
                            ) * debit_operator +
                            (
                                coalesce(balance_forwarded_entries.credit, 0) +
                                coalesce(total_crj_credit, 0) +
                                coalesce(total_cdj_credit, 0) + 
                                coalesce(total_jev_credit, 0)
                            ) * credit_operator
                        ) as ending_balance'
                ))->get();
        })->toTree();
    }

    public static function getAccountsSummary($month, $year)
    {
        return Account::withQueryConstraint(function ($query) use ($month, $year) {
            $query
                ->withCount(['children' => fn ($q) => $q->whereNull('member_id')])
                ->withSum(['recursiveCrjTransactions as total_crj_debit' => fn ($query) => $query->whereMonth('transaction_date', $month)->whereYear('transaction_date', $year)], 'debit')
                ->withSum(['recursiveCrjTransactions as total_crj_credit' => fn ($query) => $query->whereMonth('transaction_date', $month)->whereYear('transaction_date', $year)], 'credit')
                ->withSum(['recursiveCdjTransactions as total_cdj_debit' => fn ($query) => $query->whereMonth('transaction_date', $month)->whereYear('transaction_date', $year)], 'debit')
                ->withSum(['recursiveCdjTransactions as total_cdj_credit' => fn ($query) => $query->whereMonth('transaction_date', $month)->whereYear('transaction_date', $year)], 'credit')
                ->withSum(['recursiveJevTransactions as total_jev_debit' => fn ($query) => $query->whereMonth('transaction_date', $month)->whereYear('transaction_date', $year)], 'debit')
                ->withSum(['recursiveJevTransactions as total_jev_credit' => fn ($query) => $query->whereMonth('transaction_date', $month)->whereYear('transaction_date', $year)], 'credit')
                ->whereNull('accounts.member_id');
        }, function () use ($month, $year) {
            $balance_forwarded_date = Carbon::create(year: $year, month: $month)->subMonthNoOverflow();
            $joinSub = BalanceForwardedEntry::whereHas(
                'balance_forwarded_summary',
                fn ($q) => $q
                    ->whereMonth('generated_date', $balance_forwarded_date->month)
                    ->whereYear('generated_date', $balance_forwarded_date->year)
            )->groupByRaw('account_id, balance_forwarded_summary_id')->selectRaw('balance_forwarded_entries.balance_forwarded_summary_id, balance_forwarded_entries.account_id, sum(debit) as debit, sum(credit) as credit');

            return Account::tree()
                ->orderBy('sort')
                ->leftJoinSub($joinSub, 'balance_forwarded_entries', function ($join) {
                    $join->on('laravel_cte.id', '=', 'balance_forwarded_entries.account_id');
                })
                ->join('account_types', 'account_type_id', 'account_types.id')
                ->addSelect(DB::raw(
                    'balance_forwarded_entries.balance_forwarded_summary_id as balance_forwarded_summary_id,
                     balance_forwarded_entries.debit as balance_forwarded_debit, 
                     balance_forwarded_entries.credit as balance_forwarded_credit, 
                     laravel_cte.*, 
                     account_types.debit_operator, 
                     account_types.credit_operator, 
                    (
                        coalesce(total_crj_debit, 0) + 
                        coalesce(total_cdj_debit, 0) + 
                        coalesce(total_jev_debit, 0)
                    ) as total_debit, 
                    (
                        coalesce(total_crj_credit, 0) +
                        coalesce(total_cdj_credit, 0) + 
                        coalesce(total_jev_credit, 0)
                    ) as total_credit, 
                        (
                            (
                                coalesce( balance_forwarded_entries.debit, 0) + 
                                coalesce(total_crj_debit, 0) + 
                                coalesce(total_cdj_debit, 0) + 
                                coalesce(total_jev_debit, 0)
                            ) * debit_operator +
                            (
                                coalesce(balance_forwarded_entries.credit, 0) +
                                coalesce(total_crj_credit, 0) +
                                coalesce(total_cdj_credit, 0) + 
                                coalesce(total_jev_credit, 0)
                            ) * credit_operator
                        ) as ending_balance'
                ))->get();
        })->toTree();
    }
}
