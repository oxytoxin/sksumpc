<?php

namespace App\Livewire\App\Bookkeeper\Reports;

use App\Models\LoanPayment;
use App\Models\TrialBalanceEntry;
use App\Oxytoxin\Providers\TrialBalanceProvider;
use DB;
use Livewire\Attributes\Computed;
use Livewire\Attributes\On;
use Livewire\Component;

class TrialBalanceReport extends Component
{
    public $data;

    #[On('dateChanged')]
    public function dateChanged($data)
    {
        $this->data = $data;
    }

    #[Computed]
    public function CrjLoanReceivables()
    {
        return TrialBalanceProvider::getCrjLoanReceivables($this->data['month'], $this->data['year']);
    }
    #[Computed]
    public function CdjLoanReceivables()
    {
        return TrialBalanceProvider::getCdjLoanReceivables($this->data['month'], $this->data['year']);
    }

    #[Computed]
    public function CdjLoanDisbursements()
    {
        return TrialBalanceProvider::getCdjLoanDisbursements($this->data['month'], $this->data['year']);
    }

    public function render()
    {
        return view('livewire.app.bookkeeper.reports.trial-balance-report', [
            'trial_balance_entries' => TrialBalanceEntry::withDepth()->defaultOrder()->with('auditable')->get()->toFlatTree(),
        ]);
    }
}
