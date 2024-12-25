<?php

namespace App\Filament\App\Pages\Cashier\Traits;

use Filament\Actions\Action;
use Filament\Actions\StaticAction;
use Filament\Support\Enums\MaxWidth;

trait HasReceipt
{
    public function receipt(): Action
    {
        return Action::make('receipt')
            ->modalContent(fn ($arguments) => view('filament.app.pages.cashier.transaction-receipt', ['transactions' => $arguments['transactions']]))
            ->modalCancelAction(false)
            ->closeModalByClickingAway(false)
            ->modalCloseButton(false)
            ->modalSubmitAction(function (StaticAction $action) {
                $action
                    ->label('Close')
                    ->extraAttributes(['class' => 'w-full !-mt-4'], true);
            })
            ->modalWidth(MaxWidth::FourExtraLarge)
            ->modalHeading('Transaction Receipt')
            ->action(function () {
                $this->js("setTimeout(() => document.querySelector('html').style = '', 10)");
            });
    }
}
