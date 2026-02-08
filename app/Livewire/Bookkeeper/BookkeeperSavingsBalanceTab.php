<?php

namespace App\Livewire\Bookkeeper;

use App\Models\ImprestAccount;
use App\Models\LoveGiftAccount;
use App\Models\SavingsAccount;
use Carbon\CarbonImmutable;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Forms\Contracts\HasForms;
use Filament\Schemas\Schema;
use Illuminate\Support\Facades\DB;
use Livewire\Attributes\Computed;
use Livewire\Component;

class BookkeeperSavingsBalanceTab extends Component implements HasForms
{
    use InteractsWithForms;

    public $data = [];

    public function mount(): void
    {
        $this->form->fill();
    }

    public function form(Schema $schema): Schema
    {
        return $schema->components([
            DatePicker::make('to_date')
                ->label('As Of Date')
                ->live()
                ->default(today())
                ->native(false)
                ->displayFormat('m/d/Y')
                ->required(),
        ]);
    }

    #[Computed]
    public function asOfDate(): CarbonImmutable
    {
        return CarbonImmutable::parse($this->data['to_date'] ?? today());
    }

    #[Computed]
    public function totalSavingsBalance(): float
    {
        $accountIds = SavingsAccount::query()->pluck('id');

        $balances = DB::table('transactions')
            ->selectRaw('account_id, SUM(credit) as total_credit, SUM(debit) as total_debit')
            ->whereIn('account_id', $accountIds)
            ->whereDate('transaction_date', '<=', $this->asOfDate)
            ->groupBy('account_id')
            ->get();

        return $balances->sum(fn ($balance) => $balance->total_credit - $balance->total_debit);
    }

    #[Computed]
    public function totalImprestBalance(): float
    {
        $accountIds = ImprestAccount::query()->pluck('id');

        $balances = DB::table('transactions')
            ->selectRaw('account_id, SUM(credit) as total_credit, SUM(debit) as total_debit')
            ->whereIn('account_id', $accountIds)
            ->whereDate('transaction_date', '<=', $this->asOfDate)
            ->groupBy('account_id')
            ->get();

        return $balances->sum(fn ($balance) => $balance->total_credit - $balance->total_debit);
    }

    #[Computed]
    public function totalLoveGiftBalance(): float
    {
        $accountIds = LoveGiftAccount::query()->pluck('id');

        $balances = DB::table('transactions')
            ->selectRaw('account_id, SUM(credit) as total_credit, SUM(debit) as total_debit')
            ->whereIn('account_id', $accountIds)
            ->whereDate('transaction_date', '<=', $this->asOfDate)
            ->groupBy('account_id')
            ->get();

        return $balances->sum(fn ($balance) => $balance->total_credit - $balance->total_debit);
    }

    #[Computed]
    public function totalAccountBalance(): float
    {
        return $this->totalSavingsBalance + $this->totalImprestBalance + $this->totalLoveGiftBalance;
    }

    public function render()
    {
        return view('livewire.bookkeeper.bookkeeper-savings-balance-tab');
    }
}
