<?php

namespace App\Filament\App\Widgets;

use Filament\Support\Colors\Color;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

class TotalStatsOverview extends BaseWidget
{
    protected function getStats(): array
    {
        return [
            Stat::make('Total Members', '192.1k')
                ->description('...')
                ->color(Color::Green)
                ->descriptionIcon('heroicon-m-arrow-trending-up'),
            Stat::make('Total Time Deposit', '192.1k')
                ->description('...')
                ->color(Color::Green)
                ->descriptionIcon('heroicon-m-arrow-trending-up'),
            Stat::make('Total Imprest', '192.1k')
                ->description('...')
                ->color(Color::Green)
                ->descriptionIcon('heroicon-m-arrow-trending-up'),
            Stat::make('Total MSO', '192.1k')
                ->description('...')
                ->color(Color::Green)
                ->descriptionIcon('heroicon-m-arrow-trending-up'),
        ];
    }
}
