<?php

namespace App\Filament\App\Resources\SystemConfigurationResource\Pages;

use Filament\Actions\DeleteAction;
use App\Filament\App\Resources\SystemConfigurationResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditSystemConfiguration extends EditRecord
{
    protected static string $resource = SystemConfigurationResource::class;

    protected function getHeaderActions(): array
    {
        return [
            DeleteAction::make(),
        ];
    }
}
