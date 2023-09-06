<?php

namespace App\Filament\App\Pages;

use Filament\Pages\Page;

class UserAccountManagement extends Page
{
    protected static ?string $navigationIcon = 'icon-user-account-management';

    protected static string $view = 'filament.app.pages.user-account-management';

    protected static ?int $navigationSort = 12;
}
