<?php

namespace App\Filament\App\Resources\LoanApplicationResource\Pages;

use App\Filament\App\Pages\Cashier\Reports\HasSignatories;
use App\Filament\App\Resources\LoanApplicationResource;
use App\Models\LoanApplication;
use Filament\Resources\Pages\Page;

class LoanApplicationForm extends Page
{
    use HasSignatories;

    protected static string $resource = LoanApplicationResource::class;

    protected static string $view = 'filament.app.resources.loan-application-resource.pages.loan-application-form';

    public LoanApplication $loan_application;

    protected function getSignatories()
    {
        $this->signatories = collect($this->loan_application->approvals->items())->map(fn ($a) => [
            'action' => '',
            'name' => $a->name,
            'designation' => $a->position,
        ])->toArray();

        return $this->signatories;
    }

    protected function readOnlySignatories()
    {
        return true;
    }
}
