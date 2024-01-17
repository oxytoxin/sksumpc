<?php

namespace App\Filament\App\Resources\BalanceForwardedSummaryResource\Pages;

use App\Filament\App\Resources\BalanceForwardedSummaryResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditBalanceForwardedSummary extends EditRecord
{
    protected static string $resource = BalanceForwardedSummaryResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
