<?php

namespace App\Filament\App\Pages;

use Filament\Forms\Form;
use Filament\Pages\Page;
use Filament\Forms\Components\Actions;
use Filament\Forms\Components\Section;
use Filament\Forms\Contracts\HasForms;
use Filament\Forms\Components\Actions\Action;
use Filament\Forms\Concerns\InteractsWithForms;

class Reports extends Page implements HasForms
{
    use InteractsWithForms;

    protected static ?string $navigationIcon = 'icon-reports';

    protected static string $view = 'filament.app.pages.reports';

    protected static ?int $navigationSort = 14;

    public static function shouldRegisterNavigation(): bool
    {
        return auth()->user()->can('manage payments');
    }

    public function form(Form $form): Form
    {
        return $form->schema([
            Section::make('')
                ->schema([
                    Actions::make([
                        Action::make('daily_summary_savings')
                            ->label('Daily Summary for Savings')
                            ->url(route('filament.app.pages.daily-summary-savings')),
                        Action::make('daily_summary_imprests')
                            ->label('Daily Summary for Imprests')
                            ->url(route('filament.app.pages.daily-summary-imprests')),
                        Action::make('daily_summary_time_deposits')
                            ->label('Daily Summary for Time Deposits')
                            ->url(route('filament.app.pages.daily-summary-time-deposits')),
                        Action::make('daily_summary_cash_collectibles')
                            ->label('Daily Summary for Cash Collectibles')
                            ->url(route('filament.app.pages.daily-summary-cash-collectibles')),
                    ])
                ]),
        ]);
    }
}
