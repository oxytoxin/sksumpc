<?php

namespace App\Filament\App\Pages;

use Filament\Pages\Page;

class Disbursement extends Page
{
    protected static ?string $navigationIcon = 'icon-disbursement';

    protected static string $view = 'filament.app.pages.disbursement';

    protected static ?int $navigationSort = 9;
    public static function shouldRegisterNavigation(): bool
    {
        return false;
    }
}
