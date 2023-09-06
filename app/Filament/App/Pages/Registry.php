<?php

namespace App\Filament\App\Pages;

use Filament\Pages\Page;

class Registry extends Page
{
    protected static ?string $navigationIcon = 'icon-registry';

    protected static string $view = 'filament.app.pages.registry';

    protected static ?int $navigationSort = 2;
}
