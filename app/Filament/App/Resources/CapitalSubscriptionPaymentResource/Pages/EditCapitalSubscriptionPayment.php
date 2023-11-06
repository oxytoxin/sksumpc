<?php

namespace App\Filament\App\Resources\CapitalSubscriptionPaymentResource\Pages;

use App\Filament\App\Resources\CapitalSubscriptionPaymentResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditCapitalSubscriptionPayment extends EditRecord
{
    protected static string $resource = CapitalSubscriptionPaymentResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
