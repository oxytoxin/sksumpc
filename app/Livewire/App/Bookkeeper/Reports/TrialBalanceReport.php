<?php

namespace App\Livewire\App\Bookkeeper\Reports;

use App\Models\Account;
use Livewire\Component;
use Livewire\Attributes\On;
use Illuminate\Support\Carbon;
use App\Models\TransactionType;
use Livewire\Attributes\Computed;
use App\Oxytoxin\Providers\TrialBalanceProvider;
use App\Actions\BookkeeperReports\SummarizeTrialBalanceReport;
use Staudenmeir\LaravelAdjacencyList\Eloquent\Builder;

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
        return Account::withQueryConstraint(function (Builder $query) {
            $query
                ->withSum(['recursiveCrjTransactions as total_crj_debit' => fn ($query) => $query->whereMonth('transaction_date', $this->data['month'])->whereYear('transaction_date', $this->data['year'])], 'debit')
                ->withSum(['recursiveCrjTransactions as total_crj_credit' => fn ($query) => $query->whereMonth('transaction_date', $this->data['month'])->whereYear('transaction_date', $this->data['year'])], 'credit')
                ->withSum(['recursiveCdjTransactions as total_cdj_debit' => fn ($query) => $query->whereMonth('transaction_date', $this->data['month'])->whereYear('transaction_date', $this->data['year'])], 'debit')
                ->withSum(['recursiveCdjTransactions as total_cdj_credit' => fn ($query) => $query->whereMonth('transaction_date', $this->data['month'])->whereYear('transaction_date', $this->data['year'])], 'credit')
                ->withSum(['recursiveJevTransactions as total_jev_debit' => fn ($query) => $query->whereMonth('transaction_date', $this->data['month'])->whereYear('transaction_date', $this->data['year'])], 'debit')
                ->withSum(['recursiveJevTransactions as total_jev_credit' => fn ($query) => $query->whereMonth('transaction_date', $this->data['month'])->whereYear('transaction_date', $this->data['year'])], 'credit')
                ->whereNull('accounts.member_id');
        }, function () {
            return Account::tree()->get();
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
