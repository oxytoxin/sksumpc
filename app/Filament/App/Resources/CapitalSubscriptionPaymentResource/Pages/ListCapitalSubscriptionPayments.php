<?php

namespace App\Filament\App\Resources\CapitalSubscriptionPaymentResource\Pages;

use App\Filament\App\Resources\CapitalSubscriptionPaymentResource;
use Filament\Resources\Pages\ListRecords;

class ListCapitalSubscriptionPayments extends ListRecords
{
    protected static string $resource = CapitalSubscriptionPaymentResource::class;

    protected function getHeaderActions(): array
    {
        return [];
    }
}
