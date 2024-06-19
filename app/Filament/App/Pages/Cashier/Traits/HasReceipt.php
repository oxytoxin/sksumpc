<?php

namespace App\Filament\App\Pages\Cashier\Traits;

use Filament\Actions\Action as ActionsAction;
use Filament\Support\Enums\MaxWidth;

trait HasReceipt
{
    public function receipt(): ActionsAction
    {
        return ActionsAction::make('receipt')
            ->requiresConfirmation()
            ->modalContent(fn ($arguments) => view('filament.app.pages.cashier.transaction-receipt', ['transactions' => $arguments['transactions']]))
            ->modalWidth(MaxWidth::FourExtraLarge)
            ->modalCancelAction(false)
            ->modalHeading(false)
            ->closeModalByClickingAway(false)
            ->action(function ($arguments) {
            });
    }
}