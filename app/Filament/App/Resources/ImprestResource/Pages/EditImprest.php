<?php

namespace App\Filament\App\Resources\ImprestResource\Pages;

use App\Filament\App\Resources\ImprestResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditImprest extends EditRecord
{
    protected static string $resource = ImprestResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
