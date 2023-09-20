<?php

namespace App\Filament\App\Pages;

use Filament\Pages\Page;

class Accounting extends Page
{
    protected static ?string $navigationIcon = 'icon-accounting';

    protected static string $view = 'filament.app.pages.accounting';

    protected static ?int $navigationSort = 11;
    public static function shouldRegisterNavigation(): bool
    {
        return false;
    }
}
