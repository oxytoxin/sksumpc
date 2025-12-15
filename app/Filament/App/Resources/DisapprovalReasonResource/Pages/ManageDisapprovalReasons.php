<?php

namespace App\Filament\App\Resources\DisapprovalReasonResource\Pages;

use Filament\Actions\CreateAction;
use App\Filament\App\Resources\DisapprovalReasonResource;
use Filament\Actions;
use Filament\Resources\Pages\ManageRecords;

class ManageDisapprovalReasons extends ManageRecords
{
    protected static string $resource = DisapprovalReasonResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}
