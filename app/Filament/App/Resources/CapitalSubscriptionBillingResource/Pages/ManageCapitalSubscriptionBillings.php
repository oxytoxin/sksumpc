<?php

namespace App\Filament\App\Resources\CapitalSubscriptionBillingResource\Pages;

use App\Filament\App\Resources\CapitalSubscriptionBillingResource;
use Filament\Actions;
use Filament\Resources\Pages\ManageRecords;

class ManageCapitalSubscriptionBillings extends ManageRecords
{
    protected static string $resource = CapitalSubscriptionBillingResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make()
                ->createAnother(false),
        ];
    }
}
