<?php

namespace App\Filament\App\Pages;

use App\Filament\App\Pages\Cashier\Reports\CashProof;
use App\Filament\App\Pages\Cashier\Reports\CashCollectiblesReport;
use App\Filament\App\Pages\Cashier\Reports\ImprestsReport;
use App\Filament\App\Pages\Cashier\Reports\LoanPaymentsReport;
use App\Filament\App\Pages\Cashier\Reports\LoveGiftsReport;
use App\Filament\App\Pages\Cashier\Reports\SavingsReport;
use App\Filament\App\Pages\Cashier\Reports\ShareCapitalPaymentsReport;
use App\Filament\App\Pages\Cashier\Reports\TimeDepositsReport;
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

    protected static ?int $navigationSort = 4;

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
                    Tab::make('Share Capital')
                        ->schema([
                            Livewire::make(ShareCapitalPaymentsReport::class, ['report_title' => "REPORT ON MEMBERS' CBU"]),
                        ]),
                    Tab::make('Savings')
                        ->schema([
                            Livewire::make(SavingsReport::class, ['report_title' => "REPORT ON MEMBERS' SAVINGS"]),
                        ]),
                    Tab::make('Imprests')
                        ->schema([
                            Livewire::make(ImprestsReport::class, ['report_title' => "REPORT ON MEMBERS' IMPRESTS"]),
                        ]),
                    Tab::make('Love Gift')
                        ->schema([
                            Livewire::make(LoveGiftsReport::class, ['report_title' => "REPORT ON MEMBERS' LOVE GIFT"]),
                        ]),
                    Tab::make('Time Deposits')
                        ->schema([
                            Livewire::make(TimeDepositsReport::class, ['report_title' => "REPORT ON MEMBERS' TIME DEPOSITS"]),
                        ]),
                    Tab::make('Cash Collectibles')
                        ->schema([
                            Livewire::make(CashCollectiblesReport::class, ['report_title' => "REPORT ON MEMBERS' CASH COLLECTIONS"]),
                        ]),
                    Tab::make('Loan')
                        ->schema([
                            Livewire::make(LoanPaymentsReport::class, ['report_title' => "REPORT ON MEMBERS' LOAN PAYMENTS"]),
                        ]),
                    Tab::make('Cash Proof')
                        ->schema([
                            Livewire::make(CashProof::class),
                        ]),
                ]),
        ]);
    }
}
