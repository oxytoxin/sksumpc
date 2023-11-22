<?php

namespace App\Filament\App\Resources\MemberResource\Pages;

use App\Filament\App\Pages\Cashier\Reports\HasSignatories;
use App\Filament\App\Resources\MemberResource;
use App\Models\Loan;
use App\Models\User;
use App\Oxytoxin\LoansProvider;
use Filament\Resources\Pages\Page;

class LoanAmortizationSchedule extends Page
{
    use HasSignatories;

    protected static string $resource = MemberResource::class;

    protected static string $view = 'filament.app.resources.member-resource.pages.loan-amortization-schedule';

    public Loan $loan;

    public function mount()
    {
        $this->loan->load('loan_amortizations');
        $this->getSignatories();
    }

    protected function getSignatories()
    {
        $manager = User::whereRelation('roles', 'name', 'manager')->first();
        $this->signatories = [
            [
                'action' => 'Prepared by:',
                'name' => auth()->user()->name,
                'position' => 'Teller/Cashier'
            ],
            [
                'action' => 'Noted:',
                'name' => $manager?->name ?? 'FLORA C. DAMANDAMAN',
                'position' => 'Manager'
            ],
            [
                'action' => 'Conforme:',
                'name' => $this->loan->member->full_name,
                'position' => 'Manager'
            ],
        ];
    }
}
