<?php

namespace App\Livewire\App\Bookkeeper\Reports;

use App\Models\Account;
use Livewire\Component;
use Livewire\Attributes\On;
use Illuminate\Support\Carbon;
use App\Models\TransactionType;
use Livewire\Attributes\Computed;
use Illuminate\Support\Facades\DB;
use App\Oxytoxin\Providers\TrialBalanceProvider;
use Staudenmeir\LaravelAdjacencyList\Eloquent\Builder;
use App\Actions\BookkeeperReports\SummarizeTrialBalanceReport;
use App\Models\BalanceForwardedEntry;
use Illuminate\Database\Query\JoinClause;

class TrialBalanceReport extends Component
{
    public $data;

    #[On('dateChanged')]
    public function dateChanged($data)
    {
        $this->data = $data;
    }

    #[Computed]
    public function Accounts()
    {
        return Account::withQueryConstraint(function ($query) {
            $query
                ->withCount(['children' => fn ($q) => $q->whereNull('member_id')])
                ->withSum(['recursiveCrjTransactions as total_crj_debit' => fn ($query) => $query->whereMonth('transaction_date', 2)->whereYear('transaction_date', 2024)], 'debit')
                ->withSum(['recursiveCrjTransactions as total_crj_credit' => fn ($query) => $query->whereMonth('transaction_date', 2)->whereYear('transaction_date', 2024)], 'credit')
                ->withSum(['recursiveCdjTransactions as total_cdj_debit' => fn ($query) => $query->whereMonth('transaction_date', 2)->whereYear('transaction_date', 2024)], 'debit')
                ->withSum(['recursiveCdjTransactions as total_cdj_credit' => fn ($query) => $query->whereMonth('transaction_date', 2)->whereYear('transaction_date', 2024)], 'credit')
                ->withSum(['recursiveJevTransactions as total_jev_debit' => fn ($query) => $query->whereMonth('transaction_date', 2)->whereYear('transaction_date', 2024)], 'debit')
                ->withSum(['recursiveJevTransactions as total_jev_credit' => fn ($query) => $query->whereMonth('transaction_date', 2)->whereYear('transaction_date', 2024)], 'credit')
                ->whereNull('accounts.member_id');
        }, function () {
            $joinSub = BalanceForwardedEntry::whereHas('balance_forwarded_summary', fn ($q) => $q->whereMonth('generated_date', 2)->whereYear('generated_date', 2024));
            return Account::tree()
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
                        coalesce( balance_forwarded_entries.debit, 0) + 
                        coalesce(total_crj_debit, 0) + 
                        coalesce(total_cdj_debit, 0) + 
                        coalesce(total_jev_debit, 0)
                    ) as total_debit, 
                    (
                        coalesce(balance_forwarded_entries.credit, 0) +
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

    #[Computed]
    public function TransactionTypes()
    {
        return TransactionType::get();
    }

    public function render()
    {
        return view('livewire.app.bookkeeper.reports.trial-balance-report');
    }
}
