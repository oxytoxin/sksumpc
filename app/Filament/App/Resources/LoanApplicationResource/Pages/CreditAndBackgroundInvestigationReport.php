<?php

namespace App\Filament\App\Resources\LoanApplicationResource\Pages;

use App\Filament\App\Pages\Cashier\Reports\HasSignatories;
use App\Filament\App\Resources\LoanApplicationResource;
use App\Models\CreditAndBackgroundInvestigation;
use App\Models\SignatureSet;
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

    protected function getSignatureSet()
    {
        return SignatureSet::where('name', 'CIBI Reports')->first();
    }

    protected function getAdditionalSignatories()
    {
        if ($this->cibi->loan_application->desired_amount > 50000) {
            $user = User::whereRelation('roles', 'name', 'bod-chairperson')->first();
            $designation = 'BOD-Chairperson';
        } else {
            $user = User::whereRelation('roles', 'name', 'manager')->first();
            $designation = 'Manager';
        }
        return [
            [
                'user_id' => $user->id,
                'name' => $user->name,
                'action' => 'Noted by:',
                'designation' => $designation
            ]
        ];
    }
}
