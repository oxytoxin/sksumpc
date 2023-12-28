<?php

namespace App\Livewire\App\Bookkeeper\Reports;

use Livewire\Component;
use App\Models\TrialBalanceEntry;

class TrialBalanceReport extends Component
{
    public function render()
    {
        return view('livewire.app.bookkeeper.reports.trial-balance-report', [
            'trial_balance_entries' => TrialBalanceEntry::withDepth()->defaultOrder()->with('auditable')->get()->toFlatTree(),
        ]);
    }
}
