<?php

namespace App\Oxytoxin\Providers;

use App\Models\BalanceForwardedEntry;
use App\Models\BalanceForwardedSummary;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class TrialBalanceProvider
{
    public static function getBalanceForwardedEntries($month = null, $year = null)
    {
        $date = Carbon::create(month: $month, year: $year);
        $summary = BalanceForwardedSummary::query()
            ->when($month, fn ($q) => $q->whereMonth('generated_date', $date->subMonthNoOverflow()->month))
            ->when($year, fn ($q) => $q->whereYear('generated_date', $year))
            ->latest()
            ->first();
        if (!$summary)
            return collect();
        return DB::table('balance_forwarded_entries')
            ->selectRaw("sum(debit) as total_debit, sum(credit) as total_credit, trial_balance_entry_id")
            ->groupBy('trial_balance_entry_id')
            ->get()
            ->collect();
    }

    public static function getCrjLoanReceivablesTotal($month = null, $year = null)
    {
        return DB::table('loan_payments')
            ->when($month, fn ($q) => $q->whereMonth('loan_payments.transaction_date', $month))
            ->when($year, fn ($q) => $q->whereYear('loan_payments.transaction_date', $year))
            ->where('buy_out', false)
            ->selectRaw("sum(principal_payment) + sum(interest_payment) as total_amount")
            ->get()
            ->sum('total_amount');
    }

    public static function getCdjLoanReceivablesTotal($month = null, $year = null)
    {
        return DB::table('loan_payments')
            ->when($month, fn ($q) => $q->whereMonth('loan_payments.transaction_date', $month))
            ->when($year, fn ($q) => $q->whereYear('loan_payments.transaction_date', $year))
            ->where('buy_out', true)
            ->selectRaw("sum(principal_payment) + sum(interest_payment) as total_amount")
            ->get()
            ->sum('total_amount');
    }

    public static function getCdjLoanDisbursementsTotal($month = null, $year = null)
    {
        return DB::table('loans')
            ->when($month, fn ($q) => $q->whereMonth('loans.transaction_date', $month))
            ->when($year, fn ($q) => $q->whereYear('loans.transaction_date', $year))
            ->where('posted', true)
            ->selectRaw("sum(gross_amount) as total_amount")
            ->get()
            ->sum('total_amount');
    }

    public static function getJevEntries($month = null, $year = null)
    {
        return DB::table('journal_entry_voucher_items')
            ->join('journal_entry_vouchers', 'journal_entry_voucher_items.journal_entry_voucher_id', 'journal_entry_vouchers.id')
            ->when($month, fn ($q) => $q->whereMonth('journal_entry_vouchers.transaction_date', $month))
            ->when($year, fn ($q) => $q->whereYear('journal_entry_vouchers.transaction_date', $year))
            ->selectRaw("sum(debit) as total_debit, sum(credit) as total_credit, trial_balance_entry_id")
            ->groupBy('trial_balance_entry_id')
            ->get()
            ->collect();
    }

    public static function getCrjLoanReceivables($month = null, $year = null)
    {
        return DB::table('loan_payments')
            ->join('loans', 'loan_payments.loan_id', 'loans.id')
            ->when($month, fn ($q) => $q->whereMonth('loan_payments.transaction_date', $month))
            ->when($year, fn ($q) => $q->whereYear('loan_payments.transaction_date', $year))
            ->where('buy_out', false)
            ->selectRaw("sum(principal_payment) as total_principal, sum(interest_payment) as total_interest, loan_type_id")
            ->groupBy('loan_type_id')
            ->get()
            ->collect();
    }

    public static function getCdjLoanReceivables($month = null, $year = null)
    {
        return DB::table('loan_payments')
            ->join('loans', 'loan_payments.loan_id', 'loans.id')
            ->when($month, fn ($q) => $q->whereMonth('loan_payments.transaction_date', $month))
            ->when($year, fn ($q) => $q->whereYear('loan_payments.transaction_date', $year))
            ->where('buy_out', true)
            ->selectRaw("sum(principal_payment) as total_principal, sum(interest_payment) as total_interest, loan_type_id")
            ->groupBy('loan_type_id')
            ->get()
            ->collect();
    }

    public static function getCdjLoanDisbursements($month = null, $year = null)
    {
        return DB::table('loans')
            ->when($month, fn ($q) => $q->whereMonth('loans.transaction_date', $month))
            ->when($year, fn ($q) => $q->whereYear('loans.transaction_date', $year))
            ->where('posted', true)
            ->selectRaw("sum(gross_amount) as total_amount, loan_type_id")
            ->groupBy('loan_type_id')
            ->get()
            ->collect();
    }
}
