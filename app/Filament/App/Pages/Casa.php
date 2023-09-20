<?php

namespace App\Filament\App\Pages;

use Filament\Pages\Page;

class Casa extends Page
{
    protected static ?string $navigationIcon = 'icon-casa';

    protected static string $view = 'filament.app.pages.casa';

    protected static ?int $navigationSort = 13;
    public static function shouldRegisterNavigation(): bool
    {
        return false;
    }
}
