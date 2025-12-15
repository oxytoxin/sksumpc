<?php

namespace App\Filament\App\Resources\CivilStatusResource\Pages;

use Filament\Actions\CreateAction;
use App\Filament\App\Resources\CivilStatusResource;
use Filament\Actions;
use Filament\Resources\Pages\ManageRecords;

class ManageCivilStatuses extends ManageRecords
{
    protected static string $resource = CivilStatusResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}
