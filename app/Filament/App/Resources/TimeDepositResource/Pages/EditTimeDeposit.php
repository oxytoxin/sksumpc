<?php

namespace App\Filament\App\Resources\TimeDepositResource\Pages;

use App\Filament\App\Resources\TimeDepositResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditTimeDeposit extends EditRecord
{
    protected static string $resource = TimeDepositResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
