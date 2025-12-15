<?php

namespace App\Filament\App\Pages\Billings;

use Filament\Schemas\Schema;
use Filament\Schemas\Components\Tabs;
use Filament\Schemas\Components\Tabs\Tab;
use Filament\Schemas\Components\Livewire;
use App\Filament\App\Resources\CapitalSubscriptionBillingResource\Pages\ManageCapitalSubscriptionBillings;
use App\Filament\App\Resources\CashCollectibleBillingResource\Pages\ManageCashCollectibleBillings;
use App\Filament\App\Resources\LoanBillingResource\Pages\ManageLoanBillings;
use App\Filament\App\Resources\MsoBillingResource\Pages\ManageMsoBillings;
use Filament\Pages\Page;

class ManageBillings extends Page
{
    protected static ?int $navigationSort = 1;

    protected string $view = 'filament.app.pages.billings.manage-billings';

    protected static ?string $title = 'Manage Billings';

    public $data = [];

    public static function shouldRegisterNavigation(): bool
    {
        return false;
    }

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
                        Tab::make('Loan Billings')->schema([
                            Livewire::make(ManageLoanBillings::class),
                        ]),
                        Tab::make('Capital Subscription Billings')
                            ->schema([
                                Livewire::make(ManageCapitalSubscriptionBillings::class),
                            ]),
                        Tab::make('MSO Billings')
                            ->schema([
                                Livewire::make(ManageMsoBillings::class),
                            ]),
                        Tab::make('Other Billings')
                            ->schema([
                                Livewire::make(ManageCashCollectibleBillings::class),
                            ]),

                    ]),

            ]);
    }
}
