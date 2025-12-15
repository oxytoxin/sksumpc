<?php

namespace App\Filament\App\Resources\OrganizationResource\Pages;

use Filament\Actions\DeleteAction;
use App\Filament\App\Resources\OrganizationResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditOrganization extends EditRecord
{
    protected static string $resource = OrganizationResource::class;

    protected function getHeaderActions(): array
    {
        return [
            DeleteAction::make(),
        ];
    }
}
