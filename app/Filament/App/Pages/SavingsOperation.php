<?php

namespace App\Filament\App\Pages;

use Filament\Schemas\Schema;
use Filament\Schemas\Components\Tabs;
use Filament\Schemas\Components\Tabs\Tab;
use Filament\Infolists\Components\ViewEntry;
use Filament\Pages\Page;

class SavingsOperation extends Page
{
    protected static string | \BackedEnum | null $navigationIcon = 'icon-savings-operation';

    protected string $view = 'filament.app.pages.savings-operation';

    protected static ?int $navigationSort = 7;

    public static function shouldRegisterNavigation(): bool
    {
        return false;
    }

    public function infolist(Schema $schema)
    {
        return $schema
            ->components([
                Tabs::make()
                    ->tabs([
                        Tab::make('SAVINGS')
                            ->schema([
                                ViewEntry::make('summary')->view('filament.app.views.savings-regular'),
                            ]),
                        Tab::make('IMPREST')
                            ->schema([
                                ViewEntry::make('summary')->view('filament.app.views.savings-imprest'),
                            ]),
                        Tab::make('TIME DEPOSIT')
                            ->schema([
                                ViewEntry::make('summary')->view('filament.app.views.savings-time-deposit'),
                            ]),
                    ]),
            ]);
    }
}
