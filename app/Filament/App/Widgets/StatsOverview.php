<?php

namespace App\Filament\App\Widgets;

use App\Models\Member;
use Filament\Support\Colors\Color;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

class StatsOverview extends BaseWidget
{
    protected function getStats(): array
    {
        $for_cbu_renewal = Member::withSum('capital_subscriptions', 'outstanding_balance')
            ->having('capital_subscriptions_sum_outstanding_balance', '<=', 0)
            ->count();
        return [
            Stat::make('Members for CBU Renewal', $for_cbu_renewal)
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
