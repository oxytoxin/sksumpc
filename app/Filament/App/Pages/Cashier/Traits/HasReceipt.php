<?php

namespace App\Filament\App\Pages\Cashier\Traits;

use Filament\Actions\Action;
use Filament\Support\Enums\MaxWidth;

trait HasReceipt
{
    public function receipt(): Action
    {
        return Action::make('receipt')
            ->modalContent(fn($arguments) => view('filament.app.pages.cashier.transaction-receipt', ['transactions' => $arguments['transactions']]))
            ->modalWidth(MaxWidth::FourExtraLarge)
            ->modalSubmitAction(false)
            ->modalHeading('Transaction Receipt')
            ->modalCancelAction(false)
            ->action(function ($arguments) {
            });
    }
}