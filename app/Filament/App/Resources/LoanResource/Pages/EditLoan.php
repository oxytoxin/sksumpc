<?php

namespace App\Filament\App\Resources\LoanResource\Pages;

use Filament\Actions\DeleteAction;
use App\Filament\App\Resources\LoanResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditLoan extends EditRecord
{
    protected static string $resource = LoanResource::class;

    protected function getHeaderActions(): array
    {
        return [
            DeleteAction::make(),
        ];
    }
}
