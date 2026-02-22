<?php

namespace App\Livewire\Bookkeeper;

use App\Models\ImprestAccount;
use App\Models\LoveGiftAccount;
use App\Models\SavingsAccount;
use Carbon\CarbonImmutable;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Select;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Forms\Contracts\HasForms;
use Filament\Schemas\Schema;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
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
        return $schema
            ->statePath('data')
            ->components([
                DatePicker::make('to_date')
                    ->label('As Of Date')
                    ->live()
                    ->default(config('app.transaction_date'))
                    ->native(false)
                    ->displayFormat('m/d/Y')
                    ->required(),
                Select::make('savings_type')
                    ->label('Savings Type')
                    ->options([
                        'savings' => 'Savings',
                        'imprest' => 'Imprests',
                        'love_gift' => 'Love Gifts',
                    ])
                    ->live()
                    ->default('savings'),
            ]);
    }

    public function getAsOfDateProperty(): CarbonImmutable
    {
        return $this->data['to_date'] ? CarbonImmutable::parse($this->data['to_date']) : config('app.transaction_date');
    }

    public function getTotalSavingsBalanceProperty(): float
    {
        $accountIds = SavingsAccount::query()->pluck('id');

        return DB::table('transactions')
            ->selectRaw('SUM(COALESCE(credit, 0)) - SUM(COALESCE(debit, 0)) as balance')
            ->whereIn('account_id', $accountIds)
            ->whereDate('transaction_date', '<=', $this->asOfDate)
            ->value('balance') ?? 0;
    }

    public function getTotalImprestBalanceProperty(): float
    {
        $accountIds = ImprestAccount::query()->pluck('id');

        return DB::table('transactions')
            ->selectRaw('SUM(COALESCE(credit, 0)) - SUM(COALESCE(debit, 0)) as balance')
            ->whereIn('account_id', $accountIds)
            ->whereDate('transaction_date', '<=', $this->asOfDate)
            ->value('balance') ?? 0;
    }

    public function getTotalLoveGiftBalanceProperty(): float
    {
        $accountIds = LoveGiftAccount::query()->pluck('id');

        return DB::table('transactions')
            ->selectRaw('SUM(COALESCE(credit, 0)) - SUM(COALESCE(debit, 0)) as balance')
            ->whereIn('account_id', $accountIds)
            ->whereDate('transaction_date', '<=', $this->asOfDate)
            ->value('balance') ?? 0;
    }

    public function getTotalAccountBalanceProperty(): float
    {
        return $this->totalSavingsBalance + $this->totalImprestBalance + $this->totalLoveGiftBalance;
    }

    public function getSavingsAccountCountProperty(): int
    {
        $accountIds = SavingsAccount::query()->pluck('id');

        return DB::table('transactions')
            ->selectRaw('account_id, SUM(COALESCE(credit, 0)) - SUM(COALESCE(debit, 0)) as balance')
            ->whereIn('account_id', $accountIds)
            ->whereDate('transaction_date', '<=', $this->asOfDate)
            ->groupBy('account_id')
            ->having('balance', '!=', 0)
            ->count();
    }

    public function getImprestAccountCountProperty(): int
    {
        $accountIds = ImprestAccount::query()->pluck('id');

        return DB::table('transactions')
            ->selectRaw('account_id, SUM(COALESCE(credit, 0)) - SUM(COALESCE(debit, 0)) as balance')
            ->whereIn('account_id', $accountIds)
            ->whereDate('transaction_date', '<=', $this->asOfDate)
            ->groupBy('account_id')
            ->having('balance', '!=', 0)
            ->count();
    }

    public function getLoveGiftAccountCountProperty(): int
    {
        $accountIds = LoveGiftAccount::query()->pluck('id');

        return DB::table('transactions')
            ->selectRaw('account_id, SUM(COALESCE(credit, 0)) - SUM(COALESCE(debit, 0)) as balance')
            ->whereIn('account_id', $accountIds)
            ->whereDate('transaction_date', '<=', $this->asOfDate)
            ->groupBy('account_id')
            ->having('balance', '!=', 0)
            ->count();
    }

    public function getSelectedSavingsTypeProperty(): string
    {
        return $this->data['savings_type'] ?? 'savings';
    }

    public function getAccountsProperty(): Collection
    {
        $type = $this->selectedSavingsType;

        $model = match ($type) {
            'savings' => SavingsAccount::class,
            'imprest' => ImprestAccount::class,
            'love_gift' => LoveGiftAccount::class,
            default => null,
        };

        if (! $model) {
            return collect();
        }

        $accountIds = $model::query()->pluck('id');

        $balances = DB::table('transactions')
            ->selectRaw('account_id, SUM(COALESCE(credit, 0)) - SUM(COALESCE(debit, 0)) as balance')
            ->whereIn('account_id', $accountIds)
            ->whereDate('transaction_date', '<=', $this->asOfDate)
            ->groupBy('account_id')
            ->having('balance', '!=', 0)
            ->get()
            ->keyBy('account_id');

        return $model::query()
            ->with('member')
            ->whereIn('id', $balances->keys())
            ->orderBy('name')
            ->get()
            ->map(function ($account) use ($balances) {
                $account->balance = $balances[$account->id]->balance;

                return $account;
            });
    }

    public function getAccountsWithBalancesProperty(): Collection
    {
        return $this->accounts;
    }

    public function getSavingsTypeLabelProperty(): string
    {
        return match ($this->selectedSavingsType) {
            'savings' => 'Savings',
            'imprest' => 'Imprests',
            'love_gift' => 'Love Gifts',
            default => 'Accounts',
        };
    }

    public function render()
    {
        return view('livewire.bookkeeper.bookkeeper-savings-balance-tab');
    }
}
