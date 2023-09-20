<?php

namespace App\Filament\App\Pages;

use Filament\Infolists\Components\Tabs;
use Filament\Infolists\Components\Tabs\Tab;
use Filament\Infolists\Components\ViewEntry;
use Filament\Infolists\Infolist;
use Filament\Pages\Page;

class SavingsOperation extends Page
{
    protected static ?string $navigationIcon = 'icon-savings-operation';

    protected static string $view = 'filament.app.pages.savings-operation';

    protected static ?int $navigationSort = 7;

    public function infolist(Infolist $infolist)
    {
        return $infolist
            ->schema([
                Tabs::make()
                    ->tabs([
                        Tab::make('REGULAR')
                            ->schema([
                                ViewEntry::make('summary')->view('filament.app.views.savings-regular')
                            ]),
                        Tab::make('IMPREST')
                            ->schema([
                                ViewEntry::make('summary')->view('filament.app.views.savings-imprest')
                            ]),
                        Tab::make('TIME DEPOSIT')
                            ->schema([
                                ViewEntry::make('summary')->view('filament.app.views.savings-time-deposit')
                            ]),
                    ])
            ]);
    }
}
