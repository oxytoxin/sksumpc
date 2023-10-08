<?php

namespace App\Filament\App\Resources\SystemConfigurationResource\Pages;

use App\Filament\App\Resources\SystemConfigurationResource;
use Filament\Actions;
use Filament\Resources\Pages\ManageRecords;

class ManageSystemConfigurations extends ManageRecords
{
    protected static string $resource = SystemConfigurationResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make()
                ->createAnother(false),
        ];
    }
}
