<?php

namespace App\Filament\App\Pages;

use Filament\Pages\Page;

class Collection extends Page
{
    protected static string | \BackedEnum | null $navigationIcon = 'icon-collection';

    protected string $view = 'filament.app.pages.collection';

    protected static ?int $navigationSort = 10;

    public static function shouldRegisterNavigation(): bool
    {
        return false;
    }
}
