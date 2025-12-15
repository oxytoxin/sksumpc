<?php

namespace App\Filament\App\Pages\Cashier\Traits;

use Filament\Support\Enums\Width;
use Filament\Actions\Action;

trait HasReceipt
{
    public function receipt(): Action
    {
        return Action::make('receipt')
            ->modalContent(fn ($arguments) => view('filament.app.pages.cashier.transaction-receipt', ['transactions' => $arguments['transactions']]))
            ->modalCancelAction(false)
            ->closeModalByClickingAway(false)
            ->modalCloseButton(false)
            ->modalSubmitAction(function (Action $action) {
                $action
                    ->label('Close')
                    ->extraAttributes(['class' => 'w-full !-mt-4'], true);
            })
            ->modalWidth(Width::FourExtraLarge)
            ->modalHeading('Transaction Receipt')
            ->action(function () {
                $this->js("setTimeout(() => document.querySelector('html').style = '', 10)");
            });
    }
}
