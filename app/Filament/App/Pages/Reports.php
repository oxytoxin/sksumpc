<?php

namespace App\Filament\App\Pages;

use Filament\Pages\Page;

class Reports extends Page
{
    protected static ?string $navigationIcon = 'icon-reports';

    protected static string $view = 'filament.app.pages.reports';

    protected static ?int $navigationSort = 14;
}
