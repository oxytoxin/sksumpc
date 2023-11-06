<?php

namespace App\Filament\App\Resources\TimeDepositResource\Pages;

use App\Filament\App\Resources\TimeDepositResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListTimeDeposits extends ListRecords
{
    protected static string $resource = TimeDepositResource::class;

    protected function getHeaderActions(): array
    {
        return [];
    }
}
