<?php

namespace App\Filament\App\Resources\SavingResource\Pages;

use App\Filament\App\Resources\SavingResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditSaving extends EditRecord
{
    protected static string $resource = SavingResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
