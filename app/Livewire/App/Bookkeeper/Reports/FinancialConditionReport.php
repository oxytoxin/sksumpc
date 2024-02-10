<?php

namespace App\Livewire\App\Bookkeeper\Reports;

use App\Actions\BookkeeperReports\SummarizeTrialBalanceReport;
use App\Models\CashCollectible;
use App\Models\LoanType;
use App\Models\TrialBalanceEntry;
use App\Oxytoxin\Providers\FinancialStatementProvider;
use Illuminate\Support\Collection;
use Livewire\Attributes\Computed;
use Livewire\Attributes\On;
use Livewire\Component;

class FinancialConditionReport extends Component
{
    public $data;

    #[On('dateChanged')]
    public function dateChanged($data)
    {
        $this->data = $data;
    }



    #[Computed]
    public function items()
    {
        return collect();
    }

    public function render()
    {
        return view('livewire.app.bookkeeper.reports.financial-condition-report');
    }
}
