<?php

namespace App\Livewire\App;

use Filament\Actions\Contracts\HasActions;
use Filament\Actions\Concerns\InteractsWithActions;
use Filament\Schemas\Schema;
use Filament\Schemas\Components\Grid;
use DB;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Forms\Contracts\HasForms;
use Livewire\Component;

class CashFloating extends Component implements HasForms, HasActions
{
    use InteractsWithActions;
    use InteractsWithForms;

    public ?array $data = [];

    public function form(Schema $schema): Schema
    {
        return $schema
            ->components([
                Grid::make(4)
                    ->schema([
                        DatePicker::make('transaction_date')
                            ->default(today())
                            ->live()
                            ->native(false),
                    ]),
            ])
            ->statePath('data');
    }

    public function mount()
    {
        $this->form->fill();
    }

    public function render()
    {
        $withdrawals = DB::table('savings')
            ->whereDate('transaction_date', $this->data['transaction_date'] ?? today())
            ->where('amount', '<', 0)
            ->select(['reference_number', 'withdrawal', 'transaction_date'])
            ->unionAll(
                DB::table('imprests')
                    ->whereDate('transaction_date', $this->data['transaction_date'] ?? today())
                    ->where('amount', '<', 0)
                    ->select(['reference_number', 'withdrawal', 'transaction_date'])
            )
            ->unionAll(
                DB::table('time_deposits')
                    ->whereDate('withdrawal_date', $this->data['transaction_date'] ?? today())
                    ->select(['identifier', 'maturity_amount', 'withdrawal_date'])
            )
            ->get();

        return view('livewire.app.cash-floating', [
            'withdrawals' => $withdrawals,
            'cash_beginning' => auth()->user()->cashier_cash_beginnings()->whereDate('transaction_date', $this->data['transaction_date'])->first(),
        ]);
    }
}
