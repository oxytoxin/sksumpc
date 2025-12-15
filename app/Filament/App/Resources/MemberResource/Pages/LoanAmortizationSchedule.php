<?php

namespace App\Filament\App\Resources\MemberResource\Pages;

use App\Filament\App\Pages\Cashier\Reports\HasSignatories;
use App\Filament\App\Resources\MemberResource;
use App\Models\Loan;
use App\Models\SignatureSet;
use Filament\Resources\Pages\Page;

class LoanAmortizationSchedule extends Page
{
    use HasSignatories;

    protected static string $resource = MemberResource::class;

    protected string $view = 'filament.app.resources.member-resource.pages.loan-amortization-schedule';

    public Loan $loan;

    protected function getSignatureSet()
    {
        return SignatureSet::where('name', 'Loan Amortization Reports')->first();
    }

    protected function getAdditionalSignatories()
    {
        return [
            [
                'user_id' => $this->loan->member->user_id,
                'name' => $this->loan->member->full_name,
                'action' => 'Conforme:',
                'designation' => "Member's Name",
            ],
        ];
    }
}
