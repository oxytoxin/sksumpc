<?php

namespace App\Filament\App\Resources\CashCollectibleResource\Pages;

use Filament\Actions\CreateAction;
use App\Filament\App\Resources\CashCollectibleResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListCashCollectibles extends ListRecords
{
    protected static string $resource = CashCollectibleResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}
