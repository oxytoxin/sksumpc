<?php

namespace App\Filament\App\Pages;

use Filament\Pages\Page;

class ShareCapitalReports extends Page
{
    protected static string | \UnitEnum | null $navigationGroup = 'Share Capital';

    protected static ?string $navigationLabel = 'Reports';

    protected static ?int $navigationSort = 10;

    protected string $view = 'filament.app.pages.share-capital-reports';

    public static function shouldRegisterNavigation(): bool
    {
        return auth()->user()->can('manage cbu');
    }
}
