<?php

namespace App\Filament\App\Resources\MemberTypeResource\Pages;

use Filament\Actions\CreateAction;
use App\Filament\App\Resources\MemberTypeResource;
use Filament\Actions;
use Filament\Resources\Pages\ManageRecords;

class ManageMemberTypes extends ManageRecords
{
    protected static string $resource = MemberTypeResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}
