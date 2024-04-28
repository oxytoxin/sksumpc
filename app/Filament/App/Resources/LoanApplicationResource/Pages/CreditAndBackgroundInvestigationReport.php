<?php

namespace App\Filament\App\Resources\LoanApplicationResource\Pages;

use App\Filament\App\Pages\Cashier\Reports\HasSignatories;
use App\Filament\App\Resources\LoanApplicationResource;
use App\Models\CreditAndBackgroundInvestigation;
use App\Models\User;
use Filament\Resources\Pages\Page;
use Livewire\Attributes\Computed;

class CreditAndBackgroundInvestigationReport extends Page
{
    protected static string $resource = LoanApplicationResource::class;

    protected static string $view = 'filament.app.resources.loan-application-resource.pages.credit-and-background-investigation-report';

    use HasSignatories;

    public CreditAndBackgroundInvestigation $cibi;

    #[Computed]
    public function LoanApplication()
    {
        return $this->cibi->loan_application;
    }

    #[Computed]
    public function LoanApplicationMember()
    {
        return $this->cibi->loan_application->member;
    }

    protected function getSignatories()
    {
        if ($this->cibi->loan_application->desired_amount > 50000) {
            $signatory = User::whereRelation('roles', 'name', 'bod-chairperson')->first();
            $position = 'BOD-Chairperson';
        } else {
            $signatory = User::whereRelation('roles', 'name', 'manager')->first();
            $position = 'Manager';
        }

        $this->signatories = [
            [
                'action' => 'Prepared by:',
                'name' => 'JAYSON C. LANDAYAO',
                'position' => 'Credit Investigator',
            ],
            [
                'action' => 'Checked by:',
                'name' => 'JACQUILINE B. CANDIDO',
                'position' => 'Credit Committee Chairperson',
            ],
            [
                'action' => 'Noted by:',
                'name' => $signatory->name ?? 'FLORA C. DAMANDAMAN',
                'position' => $position,
            ],
        ];
    }
}
