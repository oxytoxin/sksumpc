<?php

namespace App\Filament\App\Resources\OccupationResource\Pages;

use Filament\Actions\CreateAction;
use App\Filament\App\Resources\OccupationResource;
use Filament\Actions;
use Filament\Resources\Pages\ManageRecords;

class ManageOccupations extends ManageRecords
{
    protected static string $resource = OccupationResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}
