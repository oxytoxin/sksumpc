<?php

namespace App\Filament\App\Resources\ReligionResource\Pages;

use Filament\Actions\CreateAction;
use App\Filament\App\Resources\ReligionResource;
use Filament\Actions;
use Filament\Resources\Pages\ManageRecords;

class ManageReligions extends ManageRecords
{
    protected static string $resource = ReligionResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}
