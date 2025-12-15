<?php

namespace App\Filament\App\Pages;

use Filament\Pages\Page;

class Casa extends Page
{
    protected static string | \BackedEnum | null $navigationIcon = 'icon-casa';

    protected string $view = 'filament.app.pages.casa';

    protected static ?int $navigationSort = 13;

    public static function shouldRegisterNavigation(): bool
    {
        return false;
    }
}
