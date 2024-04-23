<?php

namespace App\Filament\App\Resources\CashCollectibleBillingResource\Pages;

use App\Filament\App\Resources\CashCollectibleBillingResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditCashCollectibleBilling extends EditRecord
{
    protected static string $resource = CashCollectibleBillingResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
