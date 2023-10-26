<?php

namespace App\Filament\App\Resources\CashCollectibleResource\Pages;

use App\Filament\App\Resources\CashCollectibleResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditCashCollectible extends EditRecord
{
    protected static string $resource = CashCollectibleResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
