<?php

namespace App\Livewire\App\Bookkeeper\Reports;

use Livewire\Component;
use App\Models\LoanType;
use Livewire\Attributes\On;
use App\Models\CashCollectible;
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
        $loan_types = LoanType::get()->map(fn ($l) => [
            'name' => strtoupper($l->name),
        ]);
        return [
            [
                'title' => 'INCOME',
                'children' => [
                    [
                        'title' => 'INTEREST INCOME FROM LOANS',
                        'entries' => $loan_types,
                    ],
                ],
            ],

        ];
    }

    public function render()
    {
        return view('livewire.app.bookkeeper.reports.financial-operation-report');
    }
}
