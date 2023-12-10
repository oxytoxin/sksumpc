<?php

namespace App\Filament\App\Pages\Cashier\Reports;

use App\Models\User;
use Filament\Forms\Form;
use Filament\Pages\Page;
use Filament\Actions\Action;
use Livewire\Attributes\Computed;
use Illuminate\Contracts\View\View;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Awcodes\FilamentTableRepeater\Components\TableRepeater;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Forms\Contracts\HasForms;

class MonthlySummarySavings extends Page implements HasForms
{
    use HasSignatories, InteractsWithForms;

    protected static bool $shouldRegisterNavigation = false;

    protected static string $view = 'filament.app.pages.cashier.reports.monthly-summary-savings';

    public $data = [];

    public function form(Form $form): Form
    {
        return $form->schema([
            Select::make('month')
                ->options(oxy_get_month_range())
                ->selectablePlaceholder(false)
                ->default(today()->month)
                ->live(),
            Select::make('year')
                ->options(oxy_get_year_range())
                ->selectablePlaceholder(false)
                ->default(today()->year)
                ->live(),
        ])
            ->columns(4)
            ->statePath('data');
    }

    #[Computed]
    public function savings()
    {
        return auth()
            ->user()
            ->cashier_savings()
            ->with('savings_account')
            ->whereMonth('transaction_date', $this->data['month'])
            ->whereYear('transaction_date', $this->data['year'])
            ->get();
    }

    public function mount()
    {
        $this->form->fill();
    }
}
