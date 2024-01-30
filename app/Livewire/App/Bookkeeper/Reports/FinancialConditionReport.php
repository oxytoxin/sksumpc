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
        $cash_equivalents = TrialBalanceEntry::query()->where('category', 'cash equivalents')->pluck('id');
        $trial_balance_summary = app(SummarizeTrialBalanceReport::class)->handle(month: $this->data['month'], year: $this->data['year']);
        $cash_equivalent_entries = FinancialStatementProvider::getEntries($trial_balance_summary, $cash_equivalents, 'TOTAL CASH AND CASH EQUIVALENTS');
        $loans_receivables_entries = FinancialStatementProvider::getEntries($trial_balance_summary, TrialBalanceEntry::query()->firstWhere('name', 'loans receivables')->descendants()->pluck('id'), 'TOTAL LOAN RECEIVABLES', TrialBalanceEntry::query()->firstWhere('name', 'loans receivables')->descendants()->where('category', null)->pluck('id'), 'NET LOAN RECEIVABLES');
        return [
            [
                'type' => 'title',
                'data' => ['name' => 'ASSETS'],
            ],
            [
                'type' => 'header',
                'data' => ['name' => 'CASH AND CASH EQUIVALENTS', 'current' => 'CURRENT', 'previous' => 'PREVIOUS', 'incdec' => 'INCREASE/DECREASE'],
            ],
            ...$cash_equivalent_entries,
            [
                'type' => 'header',
                'data' => ['name' => 'LOANS RECEIVABLES', 'current' => '', 'previous' => '', 'incdec' => ''],
            ],
            ...$loans_receivables_entries,
        ];
    }

    public function render()
    {
        return view('livewire.app.bookkeeper.reports.financial-condition-report');
    }
}
