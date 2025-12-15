<?php

namespace App\Filament\App\Resources\BalanceForwardedSummaryResource\Pages;

use Filament\Actions\CreateAction;
use App\Filament\App\Resources\BalanceForwardedSummaryResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListBalanceForwardedSummaries extends ListRecords
{
    protected static string $resource = BalanceForwardedSummaryResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}
