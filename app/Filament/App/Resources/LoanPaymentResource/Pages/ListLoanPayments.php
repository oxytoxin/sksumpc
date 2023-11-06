<?php

namespace App\Filament\App\Resources\LoanPaymentResource\Pages;

use App\Filament\App\Resources\LoanPaymentResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListLoanPayments extends ListRecords
{
    protected static string $resource = LoanPaymentResource::class;

    protected function getHeaderActions(): array
    {
        return [];
    }
}
