<?php

namespace App\Filament\App\Widgets;

use Filament\Support\Colors\Color;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

class StatsOverview extends BaseWidget
{
    protected function getStats(): array
    {
        return [
            Stat::make('New Members', '192.1k')
                ->description('...')
                ->color(Color::Green)
                ->descriptionIcon('heroicon-m-arrow-trending-up'),
            Stat::make('Total Loans', '192.1k')
                ->description('...')
                ->color(Color::Green)
                ->descriptionIcon('heroicon-m-arrow-trending-up'),
            Stat::make('Total Savings', '192.1k')
                ->description('...')
                ->color(Color::Green)
                ->descriptionIcon('heroicon-m-arrow-trending-up'),
            Stat::make('Total CBU', '192.1k')
                ->description('...')
                ->color(Color::Green)
                ->descriptionIcon('heroicon-m-arrow-trending-up'),
        ];
    }
}
