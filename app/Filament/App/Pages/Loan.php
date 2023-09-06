<?php

namespace App\Filament\App\Pages;

use Filament\Pages\Page;

class Loan extends Page
{
    protected static ?string $navigationIcon = 'icon-loan';

    protected static string $view = 'filament.app.pages.loan';

    protected static ?int $navigationSort = 6;
}
