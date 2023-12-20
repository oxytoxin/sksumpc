<?php

namespace App\Filament\App\Pages;

use Filament\Forms\Form;
use Filament\Pages\Page;
use Filament\Forms\Components\Actions;
use Filament\Forms\Contracts\HasForms;
use Filament\Forms\Components\Actions\Action;
use Filament\Forms\Components\Tabs;
use Filament\Forms\Components\Tabs\Tab;
use Filament\Forms\Components\View;
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
            Tabs::make()
                ->schema([
                    Tab::make('Daily Summaries')
                        ->schema([
                            Actions::make([
                                Action::make('daily_summary_capital_subscriptions')
                                    ->label('Daily Summary for CBU')
                                    ->url(route('filament.app.pages.daily-summary-capital-subscriptions')),
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
                            ]),
                            Actions::make([
                                Action::make('daily_collection_report_mso_and_time_deposits')
                                    ->label('Daily Collection Report of MSO and Time Deposits')
                                    ->url(route('filament.app.pages.daily-collection-report-m-s-o-and-time-deposits')),
                                Action::make('daily_collection_report_ladies_dormitory')
                                    ->label('Daily Collection Report of Ladies Dormitory')
                                    ->url(route('filament.app.pages.daily-collection-report-ladies-dormitory')),
                                Action::make('daily_collection_report_rice_and_groceries')
                                    ->label('Daily Collection Report of Rice and Groceries')
                                    ->url(route('filament.app.pages.daily-collection-report-rice-and-groceries')),
                                Action::make('daily_collection_report_loan')
                                    ->label('Daily Collection Report of Loan')
                                    ->url(route('filament.app.pages.daily-collection-report-loan')),
                            ]),
                        ]),
                    Tab::make('Monthly Summaries')
                        ->schema([
                            Actions::make([
                                Action::make('monthly_summary_savings')
                                    ->label('Monthly Summary for Savings')
                                    ->url(route('filament.app.pages.monthly-summary-savings')),
                            ])
                        ]),
                    Tab::make('Cash Beginnings')
                        ->schema([
                            View::make('filament.app.views.cash-beginnings-table')
                        ]),
                    Tab::make('Cash Floating')
                        ->schema([
                            View::make('filament.app.views.cash-floating')
                        ]),
                ]),
        ]);
    }
}
