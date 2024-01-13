<?php

namespace App\Filament\App\Resources\OfficersListResource\Pages;

use App\Filament\App\Resources\OfficersListResource;
use Filament\Actions;
use Filament\Resources\Pages\ManageRecords;

class ManageOfficersLists extends ManageRecords
{
    protected static string $resource = OfficersListResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make()->createAnother(false),
        ];
    }
}
