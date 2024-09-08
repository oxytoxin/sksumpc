<?php

namespace App\Filament\App\Pages;

use App\Filament\App\Pages\Cashier\Reports\CashProof;
use App\Filament\App\Pages\Cashier\Reports\DailyCollectionsReport;
use App\Filament\App\Pages\Cashier\Reports\MsoTransactions;
use App\Filament\App\Pages\Cashier\Reports\PaymentTransactions;
use Filament\Forms\Components\Livewire;
use Filament\Forms\Components\Tabs;
use Filament\Forms\Components\Tabs\Tab;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Forms\Contracts\HasForms;
use Filament\Forms\Form;
use Filament\Pages\Page;

class Reports extends Page implements HasForms
{
    use InteractsWithForms;

    protected static string $view = 'filament.app.pages.reports';

    protected static ?string $navigationGroup = 'Cashier';

    protected static ?int $navigationSort = 5;

    public static function shouldRegisterNavigation(): bool
    {
        return auth()->user()->can('manage payments');
    }

    public function form(Form $form): Form
    {
        return $form->schema([
            Tabs::make()
                ->activeTab(1)
                ->schema([
                    Tab::make('MSO TRANSACTIONS')
                        ->schema([
                            Livewire::make(MsoTransactions::class),
                        ]),
                    Tab::make('PAYMENT TRANSACTIONS')
                        ->schema([
                            Livewire::make(PaymentTransactions::class),
                        ]),
                    Tab::make('CASH PROOF')
                        ->schema([
                            Livewire::make(CashProof::class),
                        ]),
                    Tab::make('DAILY COLLECTIONS')
                        ->schema([
                            Livewire::make(DailyCollectionsReport::class),
                        ]),
                ]),
        ]);
    }
}
