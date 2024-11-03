<?php

namespace App\Filament\App\Pages\Cashier;

use App\Models\SystemConfiguration;
use Filament\Forms\Components\Livewire;
use Filament\Forms\Components\Tabs;
use Filament\Forms\Components\Tabs\Tab;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Forms\Contracts\HasForms;
use Filament\Forms\Form;
use Filament\Pages\Page;
use Livewire\Attributes\On;

class CashierTransactionsPage extends Page implements HasForms
{
    use InteractsWithForms;

    protected static string $view = 'filament.app.pages.cashier.cashier-transactions-page';

    protected static ?string $navigationGroup = 'Cashier';
    protected ?string $heading = 'Transactions';
    protected static ?string $navigationLabel = 'Transactions';
    protected static ?int $navigationSort = 2;

    public static function shouldRegisterNavigation(): bool
    {
        return auth()->user()->can('manage payments');
    }

    public $data = [];

    public function mount(): void
    {
        $this->form->fill();
    }

    public function form(Form $form): Form
    {
        return $form
            ->statePath('data')
            ->schema([
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
