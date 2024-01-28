<?php

namespace App\Livewire\App\Bookkeeper\Reports;

use App\Actions\TrialBalance\SummarizeTrialBalanceReport;
use App\Oxytoxin\Providers\TrialBalanceProvider;
use Illuminate\Support\Carbon;
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
    public function TrialBalanceEntries()
    {
        return app(SummarizeTrialBalanceReport::class)->handle($this->data['month'], $this->data['year']);
    }

    #[Computed]
    public function TrialBalanceHeaderColumns()
    {
        return collect(TrialBalanceProvider::getTrialBalanceColumns())->map(fn ($column) => str($column)->replace(['CREDIT', 'DEBIT'], '')->trim())->unique();
    }

    public function render()
    {
        return view('livewire.app.bookkeeper.reports.trial-balance-report');
    }
}
