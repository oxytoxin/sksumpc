<?php

namespace App\Filament\App\Pages;

use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Contracts\HasForms;
use Filament\Pages\Page;

class Billing extends Page implements HasForms
{
    protected static ?string $navigationIcon = 'icon-billing';

    protected static string $view = 'filament.app.pages.billing';

    protected static ?int $navigationSort = 8;

    protected function getFormSchema(): array
    {
        return [
            DateTimePicker::make('date')
                ->seconds(false)
        ];
    }
}
