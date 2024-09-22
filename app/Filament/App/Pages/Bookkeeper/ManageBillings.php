<?php

namespace App\Filament\App\Pages\Bookkeeper;

use App\Filament\App\Pages\Cashier\BillingTransactions;
use App\Filament\App\Pages\Cashier\NewAccountTransactions;
use App\Filament\App\Pages\Cashier\PaymentTransactions;
use App\Filament\App\Resources\CapitalSubscriptionBillingResource\Pages\ManageCapitalSubscriptionBillings;
use App\Filament\App\Resources\CashCollectibleBillingResource\Pages\ManageCashCollectibleBillings;
use App\Filament\App\Resources\LoanBillingResource\Pages\ManageLoanBillings;
use Filament\Forms\Components\Livewire;
use Filament\Forms\Components\Tabs;
use Filament\Forms\Components\Tabs\Tab;
use Filament\Forms\Form;
use Filament\Pages\Page;

class ManageBillings extends Page
{
    protected static ?int $navigationSort = 1;

    protected static ?string $navigationGroup = 'Bookkeeping';

    protected static string $view = 'filament.app.pages.bookkeeper.manage-billings';

    public $data = [];

    public static function shouldRegisterNavigation(): bool
    {
        return auth()->user()->can('manage bookkeeping');
    }

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
                        Tab::make('Loan Billings')->schema([
                            Livewire::make(ManageLoanBillings::class),
                        ]),
                        Tab::make('Capital Subscription Billings')
                            ->schema([
                                Livewire::make(ManageCapitalSubscriptionBillings::class),
                            ]),
                        Tab::make('Other Billings')
                            ->schema([
                                Livewire::make(ManageCashCollectibleBillings::class),
                            ]),

                    ]),

            ]);
    }
}
