<?php

namespace App\Filament\App\Pages;

use Filament\Schemas\Schema;
use Filament\Schemas\Components\Tabs;
use Filament\Schemas\Components\Tabs\Tab;
use Filament\Schemas\Components\Livewire;
use App\Filament\App\Pages\Cashier\Reports\BillingTransactions;
use App\Filament\App\Pages\Cashier\Reports\CashProof;
use App\Filament\App\Pages\Cashier\Reports\DailyCollectionsReport;
use App\Filament\App\Pages\Cashier\Reports\MsoTransactions;
use App\Filament\App\Pages\Cashier\Reports\PaymentTransactions;
use App\Filament\App\Pages\Cashier\RequiresBookkeeperTransactionDate;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Forms\Contracts\HasForms;
use Filament\Pages\Page;

class Reports extends Page implements HasForms
{
    use InteractsWithForms, RequiresBookkeeperTransactionDate;

    protected string $view = 'filament.app.pages.reports';

    protected static string | \UnitEnum | null $navigationGroup = 'Cashier';

    protected static ?int $navigationSort = 5;

    public static function shouldRegisterNavigation(): bool
    {
        return auth()->user()->can('manage payments');
    }

    public function form(Schema $schema): Schema
    {
        return $schema->components([
            Tabs::make()
                ->activeTab(1)
                ->persistTabInQueryString()
                ->schema([
                    Tab::make('MSO TRANSACTIONS')
                        ->schema([
                            Livewire::make(MsoTransactions::class),
                        ]),
                    Tab::make('OR TRANSACTIONS')
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
                    Tab::make('BILLING')
                        ->schema([
                            Livewire::make(BillingTransactions::class),
                        ]),
                ]),
        ]);
    }
}
