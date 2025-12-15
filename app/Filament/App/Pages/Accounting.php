<?php

namespace App\Filament\App\Pages;

use Filament\Pages\Page;

class Accounting extends Page
{
    protected static string | \BackedEnum | null $navigationIcon = 'icon-accounting';

    protected string $view = 'filament.app.pages.accounting';

    protected static ?int $navigationSort = 11;

    public static function shouldRegisterNavigation(): bool
    {
        return false;
    }
}
