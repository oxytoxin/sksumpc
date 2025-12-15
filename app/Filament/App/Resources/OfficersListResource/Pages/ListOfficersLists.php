<?php

namespace App\Filament\App\Resources\OfficersListResource\Pages;

use Filament\Actions\CreateAction;
use App\Filament\App\Resources\OfficersListResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListOfficersLists extends ListRecords
{
    protected static string $resource = OfficersListResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}
