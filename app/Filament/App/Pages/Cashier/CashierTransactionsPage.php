<?php

namespace App\Filament\App\Pages\Cashier;

use Filament\Schemas\Schema;
use Filament\Schemas\Components\Tabs;
use Filament\Schemas\Components\Tabs\Tab;
use Filament\Schemas\Components\Livewire;
use Auth;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Forms\Contracts\HasForms;
use Filament\Pages\Page;

class CashierTransactionsPage extends Page implements HasForms
{
    use InteractsWithForms;

    protected string $view = 'filament.app.pages.cashier.cashier-transactions-page';

    protected static string | \UnitEnum | null $navigationGroup = 'Cashier';

    protected ?string $heading = 'Transactions';

    protected static ?string $navigationLabel = 'Transactions';

    protected static ?int $navigationSort = 2;

    public static function shouldRegisterNavigation(): bool
    {
        return Auth::user()->can('manage payments');
    }

    public $data = [];

    public function mount(): void
    {
        $this->form->fill();
    }

    public function form(Schema $schema): Schema
    {
        return $schema
            ->statePath('data')
            ->components([
                Tabs::make('transactions')
                    ->activeTab(1)
                    ->persistTabInQueryString()
                    ->tabs([
                        Tab::make('General Payments')->schema([
                            Livewire::make(PaymentTransactions::class),
                        ]),
                        Tab::make('Billings')
                            ->schema([
                                Livewire::make(BillingTransactions::class),
                            ]),
                        Tab::make('Accounts')
                            ->schema([
                                Livewire::make(NewAccountTransactions::class),
                            ]),

                    ]),

            ]);
    }
}
