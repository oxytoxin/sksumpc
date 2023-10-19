<?php

namespace App\Filament\App\Resources\DivisionResource\Pages;

use App\Filament\App\Resources\DivisionResource;
use Filament\Actions;
use Filament\Resources\Pages\ManageRecords;

class ManageDivisions extends ManageRecords
{
    protected static string $resource = DivisionResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
