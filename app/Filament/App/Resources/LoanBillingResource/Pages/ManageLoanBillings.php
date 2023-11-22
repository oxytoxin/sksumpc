<?php

namespace App\Filament\App\Resources\LoanBillingResource\Pages;

use App\Filament\App\Resources\LoanBillingResource;
use Filament\Actions;
use Filament\Resources\Pages\ManageRecords;

class ManageLoanBillings extends ManageRecords
{
    protected static string $resource = LoanBillingResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make()
                ->createAnother(false),
        ];
    }
}
