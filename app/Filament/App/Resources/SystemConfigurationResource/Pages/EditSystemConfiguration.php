<?php

namespace App\Filament\App\Resources\SystemConfigurationResource\Pages;

use App\Filament\App\Resources\SystemConfigurationResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditSystemConfiguration extends EditRecord
{
    protected static string $resource = SystemConfigurationResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
