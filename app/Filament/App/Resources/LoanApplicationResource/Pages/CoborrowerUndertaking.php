<?php

namespace App\Filament\App\Resources\LoanApplicationResource\Pages;

use App\Filament\App\Resources\LoanApplicationResource;
use App\Models\LoanApplication;
use Filament\Resources\Pages\Page;

class CoborrowerUndertaking extends Page
{
    protected static string $resource = LoanApplicationResource::class;

    protected static string $view = 'filament.app.resources.loan-application-resource.pages.coborrower-undertaking';

    public LoanApplication $loan_application;


}
