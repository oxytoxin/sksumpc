<?php

namespace App\Filament\App\Pages;

use Filament\Pages\Page;

class FundAllocator extends Page
{
    protected static string | \BackedEnum | null $navigationIcon = 'icon-fund-allocator';

    protected string $view = 'filament.app.pages.fund-allocator';

    protected static ?int $navigationSort = 5;

    public static function shouldRegisterNavigation(): bool
    {
        return false;
    }
}
