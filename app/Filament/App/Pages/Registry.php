<?php

namespace App\Filament\App\Pages;

use Filament\Pages\Page;

class Registry extends Page
{
    protected static string | \BackedEnum | null $navigationIcon = 'icon-registry';

    protected string $view = 'filament.app.pages.registry';

    protected static ?int $navigationSort = 2;

    public static function shouldRegisterNavigation(): bool
    {
        return false;
    }
}
