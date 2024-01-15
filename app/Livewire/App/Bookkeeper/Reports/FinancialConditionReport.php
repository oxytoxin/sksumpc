<?php

namespace App\Livewire\App\Bookkeeper\Reports;

use App\Models\CashCollectible;
use App\Models\LoanType;
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
        $loan_types = LoanType::get()->map(fn ($l) => [
            'name' => strtoupper($l->name),
        ]);
        $cash_collectibles = CashCollectible::get()->map(fn ($cc) => [
            'name' => strtoupper($cc->name)
        ]);
        return [
            [
                'title' => 'ASSETS',
                'children' => [
                    [
                        'title' => 'CASH AND CASH EQUIVALENTS',
                        'entries' => [
                            [
                                'name' => 'CASH ON HAND',
                            ],
                            [
                                'name' => 'CASH IN BANK- DBP (GENERAL FUND)',
                            ],
                            [
                                'name' => 'CASH IN BANK- DBP (MSO FUND)',
                            ],
                            [
                                'name' => 'CASH IN BANK- LBP',
                            ],
                            [
                                'name' => 'PETTY CASH FUND',
                            ],
                            [
                                'name' => 'REVOLVING FUND',
                            ],
                        ],
                    ],
                    [
                        'title' => 'LOANS RECEIVABLES',
                        'entries' => $loan_types,
                    ],
                    [
                        'title' => 'ACCOUNTS RECEIVABLES',
                        'entries' => $cash_collectibles,
                    ],
                ],
            ],
            [
                'title' => 'LIABILITIES',
                'children' => [
                    [
                        'title' => 'DEPOSIT LIABILITIES',
                        'entries' => [
                            [
                                'name' => 'SAVINGS DEPOSIT',
                            ],
                            [
                                'name' => 'TIME DEPOSIT',
                            ],
                        ],
                    ],
                ],
            ],
            [
                'title' => 'MEMBERS EQUITY',
                'children' => [
                    [
                        'title' => 'SHARE CAPITAL COMMON',
                        'entries' => [
                            [
                                'name' => 'SUBSCRIBED SHARE CAPITAL',
                            ],
                            [
                                'name' => 'LESS: SUBSCRIPTION RECEIVABLES',
                            ],
                            [
                                'name' => 'PAID UP SHARE CAPITAL',
                            ],
                            [
                                'name' => 'DEPOSIT ON SHARE CAPITAL',
                            ],
                        ],
                    ],
                    [
                        'title' => 'SHARE CAPITAL PREFERRED',
                        'entries' => [
                            [
                                'name' => 'SUBSCRIBED SHARE CAPITAL',
                            ],
                            [
                                'name' => 'LESS: SUBSCRIPTION RECEIVABLES',
                            ],
                            [
                                'name' => 'PAID UP SHARE CAPITAL',
                            ],
                            [
                                'name' => 'DEPOSIT ON SHARE CAPITAL',
                            ],
                        ],
                    ],
                ],
            ],
        ];
    }

    public function render()
    {
        return view('livewire.app.bookkeeper.reports.financial-condition-report');
    }
}
