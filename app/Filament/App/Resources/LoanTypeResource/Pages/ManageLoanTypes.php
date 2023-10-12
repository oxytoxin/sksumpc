<?php

namespace App\Filament\App\Resources\LoanTypeResource\Pages;

use App\Filament\App\Resources\LoanTypeResource;
use Filament\Actions;
use Filament\Resources\Pages\ManageRecords;

class ManageLoanTypes extends ManageRecords
{
    protected static string $resource = LoanTypeResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
