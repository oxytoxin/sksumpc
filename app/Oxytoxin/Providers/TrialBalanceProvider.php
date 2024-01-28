<?php

namespace App\Oxytoxin\Providers;

use App\Models\BalanceForwardedEntry;
use App\Models\BalanceForwardedSummary;
use App\Models\DisbursementVoucherItem;
use App\Models\TrialBalanceEntry;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class TrialBalanceProvider
{
    public static function getBalanceForwardedEntries($month = null, $year = null)
    {
        $summary = BalanceForwardedSummary::query()
            ->when($month, fn ($q) => $q->whereMonth('generated_date', $month))
            ->when($year, fn ($q) => $q->whereYear('generated_date', $year))
            ->latest()
            ->first();
        if (!$summary)
            return collect();
        return DB::table('balance_forwarded_entries')
            ->where('balance_forwarded_summary_id', $summary->id)
            ->selectRaw("sum(debit) as total_debit, sum(credit) as total_credit, trial_balance_entry_id")
            ->groupBy('trial_balance_entry_id')
            ->get()
            ->collect();
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

    public static function getTrialBalanceEntries()
    {
        return TrialBalanceEntry::withDepth()->defaultOrder()->with('auditable', 'parent')->get()->toFlatTree();
    }

    public static function getTrialBalanceColumns()
    {
        return [
            'BALANCE FORWARDED DEBIT',
            'BALANCE FORWARDED CREDIT',
            'CRJ-LOANS DEBIT',
            'CRJ-LOANS CREDIT',
            'CRJ-OTHERS DEBIT',
            'CRJ-OTHERS CREDIT',
            'CRJ-MSO DEBIT',
            'CRJ-MSO CREDIT',
            'CRJ-RICE DEBIT',
            'CRJ-RICE CREDIT',
            'CRJ-LAB DEBIT',
            'CRJ-LAB CREDIT',
            'CRJ-DORM DEBIT',
            'CRJ-DORM CREDIT',
            'CRJ TOTAL DEBIT',
            'CRJ TOTAL CREDIT',
            'CDJ-LOANS DEBIT',
            'CDJ-LOANS CREDIT',
            'CDJ-OTHERS DEBIT',
            'CDJ-OTHERS CREDIT',
            'CDJ-MSO DEBIT',
            'CDJ-MSO CREDIT',
            'CDJ-RICE DEBIT',
            'CDJ-RICE CREDIT',
            'CDJ TOTAL DEBIT',
            'CDJ TOTAL CREDIT',
            'JEV DEBIT',
            'JEV CREDIT',
            'ENDING BALANCE DEBIT',
            'ENDING BALANCE CREDIT',
        ];
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

    public static function getCdjEntries($month = null, $year = null)
    {
        return DisbursementVoucherItem::query()
            ->join('disbursement_vouchers', 'disbursement_vouchers.id', 'disbursement_voucher_items.disbursement_voucher_id')
            ->whereMonth('transaction_date', $month)
            ->whereYear('transaction_date', $year)
            ->selectRaw("sum(debit) as total_debit, sum(credit) as total_credit,trial_balance_entry_id, cdj_column")
            ->groupBy('trial_balance_entry_id', 'cdj_column')
            ->get()
            ->collect()
            ->groupBy('cdj_column');
    }
}
