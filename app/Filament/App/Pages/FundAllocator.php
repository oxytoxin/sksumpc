<?php

namespace App\Filament\App\Pages;

use Filament\Pages\Page;

class FundAllocator extends Page
{
    protected static ?string $navigationIcon = 'icon-fund-allocator';

    protected static string $view = 'filament.app.pages.fund-allocator';

    protected static ?int $navigationSort = 5;

    public static function shouldRegisterNavigation(): bool
    {
        return false;
    }
}
