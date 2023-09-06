<?php

namespace App\Filament\App\Pages;

use Filament\Pages\Page;

class Membership extends Page
{
    protected static ?string $navigationIcon = 'icon-membership';

    protected static string $view = 'filament.app.pages.membership';

    protected static ?int $navigationSort = 3;
}
