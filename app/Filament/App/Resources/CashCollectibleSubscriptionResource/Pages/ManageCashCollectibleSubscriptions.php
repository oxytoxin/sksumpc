<?php

namespace App\Filament\App\Resources\CashCollectibleSubscriptionResource\Pages;

use App\Filament\App\Resources\CashCollectibleSubscriptionResource;
use Filament\Actions;
use Filament\Resources\Pages\ManageRecords;

class ManageCashCollectibleSubscriptions extends ManageRecords
{
    protected static string $resource = CashCollectibleSubscriptionResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
