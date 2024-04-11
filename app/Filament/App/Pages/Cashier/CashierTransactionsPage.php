<?php

namespace App\Filament\App\Pages\Cashier;

use Filament\Forms\Components\Livewire;
use Filament\Forms\Components\Tabs;
use Filament\Forms\Components\Tabs\Tab;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Forms\Contracts\HasForms;
use Filament\Forms\Form;
use Filament\Pages\Page;

class CashierTransactionsPage extends Page implements HasForms
{
    use InteractsWithForms;

    protected static ?string $navigationIcon = 'heroicon-o-document-text';

    protected static string $view = 'filament.app.pages.cashier.cashier-transactions-page';

    protected static ?int $navigationSort = 4;

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
                        Tab::make('Payments')->schema([
                            Livewire::make(PaymentTransactions::class),
                        ]),
                        Tab::make('Billings')
                            ->schema([
                                Livewire::make(BillingTransactions::class),
                            ]),
                    ]),

            ]);
    }
}
