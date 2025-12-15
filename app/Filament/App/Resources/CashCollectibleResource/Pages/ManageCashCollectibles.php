<?php

namespace App\Filament\App\Resources\CashCollectibleResource\Pages;

use Filament\Actions\CreateAction;
use App\Filament\App\Resources\CashCollectibleResource;
use Filament\Actions;
use Filament\Resources\Pages\ManageRecords;

class ManageCashCollectibles extends ManageRecords
{
    protected static string $resource = CashCollectibleResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}
