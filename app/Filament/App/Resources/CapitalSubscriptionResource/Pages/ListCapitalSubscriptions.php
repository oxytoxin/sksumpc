<?php

namespace App\Filament\App\Resources\CapitalSubscriptionResource\Pages;

use App\Filament\App\Resources\CapitalSubscriptionResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListCapitalSubscriptions extends ListRecords
{
    protected static string $resource = CapitalSubscriptionResource::class;

    protected function getHeaderActions(): array
    {
        return [];
    }
}
