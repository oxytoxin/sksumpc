<?php

namespace App\Livewire\App\Bookkeeper\Reports;

use App\Actions\BookkeeperReports\SummarizeTrialBalanceReport;
use Livewire\Component;
use App\Models\LoanType;
use Livewire\Attributes\On;
use App\Models\CashCollectible;
use App\Models\TrialBalanceEntry;
use App\Oxytoxin\Providers\FinancialStatementProvider;
use Livewire\Attributes\Computed;

class FinancialOperationReport extends Component
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
        return view('livewire.app.bookkeeper.reports.financial-operation-report');
    }
}
