<?php

namespace App\Filament\App\Resources\CashCollectibleBillingResource\Pages;

use App\Filament\App\Resources\CashCollectibleBillingResource;
use Filament\Actions;
use Filament\Resources\Pages\ManageRecords;

class ManageCashCollectibleBillings extends ManageRecords
{
    protected static string $resource = CashCollectibleBillingResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
