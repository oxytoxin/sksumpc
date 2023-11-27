<?php

namespace App\Filament\App\Resources\LoanBillingResource\Pages;

use App\Filament\App\Resources\LoanBillingResource;
use App\Models\LoanBilling;
use Filament\Resources\Pages\Page;

class PrintLoanBilling extends Page
{
    protected static string $resource = LoanBillingResource::class;

    protected static string $view = 'filament.app.resources.loan-billing-resource.pages.print-loan-billing';

    public LoanBilling $loan_billing;
}
