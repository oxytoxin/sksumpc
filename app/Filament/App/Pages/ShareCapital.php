<?php

namespace App\Filament\App\Pages;

use Filament\Infolists\Components\Tabs;
use Filament\Infolists\Components\Tabs\Tab;
use Filament\Infolists\Components\ViewEntry;
use Filament\Infolists\Infolist;
use Filament\Pages\Page;

class ShareCapital extends Page
{
    protected static ?string $navigationIcon = 'icon-share-capital';

    protected static string $view = 'filament.app.pages.share-capital';

    protected static ?int $navigationSort = 1;

    protected static ?string $navigationGroup = 'Transactions History';

    public static function shouldRegisterNavigation(): bool
    {
        return auth()->user()->canAny(['manage cbu', 'manage bookkeeping']);
    }

    public function infolist(Infolist $infolist)
    {
        return $infolist
            ->schema([
                Tabs::make()
                    ->tabs([
                        Tab::make('Summary')
                            ->schema([
                                ViewEntry::make('summary')->view('filament.app.views.cbu-summary')
                            ]),

                    ])
            ]);
    }
}
