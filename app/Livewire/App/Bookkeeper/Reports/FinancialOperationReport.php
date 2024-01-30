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
        $loan_interests = TrialBalanceEntry::query()->where('category', 'interest income from loans')->pluck('id');
        $trial_balance_summary = app(SummarizeTrialBalanceReport::class)->handle(month: $this->data['month'], year: $this->data['year']);

        $loan_interests_entries = FinancialStatementProvider::getEntries(summary: $trial_balance_summary, ids: $loan_interests, total_name: 'TOTAL INTEREST INCOME FROM LOANS', debit: false);
        return [
            [
                'type' => 'title',
                'data' => ['name' => 'INCOME'],
            ],
            [
                'type' => 'header',
                'data' => ['name' => 'INTEREST INCOME FROM LOANS', 'current' => 'CURRENT', 'previous' => 'PREVIOUS', 'incdec' => 'INCREASE/DECREASE'],
            ],
            ...$loan_interests_entries,
        ];
    }

    public function render()
    {
        return view('livewire.app.bookkeeper.reports.financial-operation-report');
    }
}
