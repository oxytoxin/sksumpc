<?php

namespace App\Filament\App\Pages\Cashier\Reports;

use App\Models\Imprest;
use App\Models\Saving;
use Filament\Forms\Form;
use Filament\Pages\Page;
use Livewire\Attributes\Computed;
use Malzariey\FilamentDaterangepickerFilter\Fields\DateRangePicker;

class CashProof extends Page
{
    use HasSignatories;

    protected static bool $shouldRegisterNavigation = false;

    protected static string $view = 'filament.app.pages.cashier.reports.cash-proof';

    public $data = [];

    public function form(Form $form): Form
    {
        return $form
            ->schema([
                DateRangePicker::make('transaction_date')
                    ->format('Y/m/d')
                    ->default(config('app.transaction_date'))
                    ->displayFormat('YYYY/MM/DD')
                    ->reactive(),
            ])
            ->columns(4)
            ->statePath('data');
    }

    #[Computed]
    public function TotalDeposits()
    {
        $total_savings_deposit = Saving::query()->whereIn('payment_type_id', [1, 3, 4])->when($this->data['transaction_date'] ?? today()->format('m/d/Y - m/d/Y'), fn ($q, $v) => $q->whereBetween('transaction_date', explode(' - ', $v)))->sum('deposit');
        $total_imprest_deposit = Imprest::query()->whereIn('payment_type_id', [1, 3, 4])->when($this->data['transaction_date'] ?? today()->format('m/d/Y - m/d/Y'), fn ($q, $v) => $q->whereBetween('transaction_date', explode(' - ', $v)))->sum('deposit');

        return $total_savings_deposit + $total_imprest_deposit;
    }

    #[Computed]
    public function TotalWithdrawals()
    {
        $total_savings_withdrawal = Saving::query()->whereIn('payment_type_id', [1, 3, 4])->when($this->data['transaction_date'] ?? today()->format('Y/m/d - Y/m/d'), fn ($q, $v) => $q->whereBetween('transaction_date', explode(' - ', $v)))->sum('withdrawal');
        $total_imprest_withdrawal = Imprest::query()->whereIn('payment_type_id', [1, 3, 4])->when($this->data['transaction_date'] ?? today()->format('Y/m/d - Y/m/d'), fn ($q, $v) => $q->whereBetween('transaction_date', explode(' - ', $v)))->sum('withdrawal');

        return $total_savings_withdrawal + $total_imprest_withdrawal;
    }

    public function mount()
    {
        $this->form->fill();
        $this->getSignatories();
        data_set($this, 'data.transaction_date', (config('app.transaction_date')?->format('Y/m/d') ?? today()->format('Y/m/d')).' - '.(config('app.transaction_date')?->format('Y/m/d') ?? today()->format('Y/m/d')));
    }
}
