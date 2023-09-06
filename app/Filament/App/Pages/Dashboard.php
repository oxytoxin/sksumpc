<?php

namespace App\Filament\App\Pages;

use App\Filament\App\Widgets\CbuChart;
use App\Filament\App\Widgets\IncomeChart;
use App\Filament\App\Widgets\StatsOverview as NewStatsOverview;
use App\Filament\App\Widgets\TotalStatsOverview;
use Filament\Pages\Page;

class Dashboard extends Page
{
    protected static ?string $navigationIcon = 'icon-dashboard';

    protected static string $view = 'filament.app.pages.dashboard-page';

    protected static ?int $navigationSort = 1;

    protected ?string $heading = '';

    protected function getHeaderWidgets(): array
    {
        return [
            NewStatsOverview::class,
            CbuChart::class,
            IncomeChart::class,
            TotalStatsOverview::class
        ];
    }
}
