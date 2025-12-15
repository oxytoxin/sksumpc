<?php

namespace App\Filament\App\Resources\OrganizationResource\Pages;

use Filament\Actions\CreateAction;
use App\Filament\App\Resources\OrganizationResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListOrganizations extends ListRecords
{
    protected static string $resource = OrganizationResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}
