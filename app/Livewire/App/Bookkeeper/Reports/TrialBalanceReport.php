<?php

namespace App\Livewire\App\Bookkeeper\Reports;

use App\Models\TrialBalanceEntry;
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

    public function render()
    {
        return view('livewire.app.bookkeeper.reports.trial-balance-report', [
            'trial_balance_entries' => TrialBalanceEntry::withDepth()->defaultOrder()->with('auditable')->get()->toFlatTree(),
        ]);
    }
}
