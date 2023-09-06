<?php

namespace App\Filament\App\Pages;

use Filament\Pages\Page;

class Collection extends Page
{
    protected static ?string $navigationIcon = 'icon-collection';

    protected static string $view = 'filament.app.pages.collection';

    protected static ?int $navigationSort = 10;
}
