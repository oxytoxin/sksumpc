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
                        'regular_savings' => 'Regular Savings (1011, 2111)',
                        'associate_savings' => 'Associate Savings (1012)',
                        'laboratory_savings' => 'Laboratory Savings (1013)',
                        'imprest' => 'Imprests',
                        'love_gift' => 'Love Gifts',
                    ])
                    ->live()
                    ->default('regular_savings'),
            ]);
    }

    public function getAsOfDateProperty(): CarbonImmutable
    {
        return $this->data['to_date'] ? CarbonImmutable::parse($this->data['to_date']) : config('app.transaction_date');
    }

    public function getRegularSavingsBalanceProperty(): float
    {
        $accountIds = SavingsAccount::query()->where(function ($query) {
            $query->where('number', 'like', '1011%')
                ->orWhere('number', 'like', '2111%');
        })->pluck('id');

        return DB::table('transactions')
            ->selectRaw('SUM(COALESCE(credit, 0)) - SUM(COALESCE(debit, 0)) as balance')
            ->whereIn('account_id', $accountIds)
            ->whereDate('transaction_date', '<=', $this->asOfDate)
            ->value('balance') ?? 0;
    }

    public function getAssociateSavingsBalanceProperty(): float
    {
        $accountIds = SavingsAccount::query()->where('number', 'like', '1012%')->pluck('id');

        return DB::table('transactions')
            ->selectRaw('SUM(COALESCE(credit, 0)) - SUM(COALESCE(debit, 0)) as balance')
            ->whereIn('account_id', $accountIds)
            ->whereDate('transaction_date', '<=', $this->asOfDate)
            ->value('balance') ?? 0;
    }

    public function getLaboratorySavingsBalanceProperty(): float
    {
        $accountIds = SavingsAccount::query()->where('number', 'like', '1013%')->pluck('id');

        return DB::table('transactions')
            ->selectRaw('SUM(COALESCE(credit, 0)) - SUM(COALESCE(debit, 0)) as balance')
            ->whereIn('account_id', $accountIds)
            ->whereDate('transaction_date', '<=', $this->asOfDate)
            ->value('balance') ?? 0;
    }

    public function getTotalSavingsBalanceProperty(): float
    {
        return $this->regularSavingsBalance + $this->associateSavingsBalance + $this->laboratorySavingsBalance;
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
        return $this->regularSavingsBalance + $this->associateSavingsBalance + $this->laboratorySavingsBalance + $this->totalImprestBalance + $this->totalLoveGiftBalance;
    }

    public function getRegularSavingsAccountCountProperty(): int
    {
        $accountIds = SavingsAccount::query()->where(function ($query) {
            $query->where('number', 'like', '1011%')
                ->orWhere('number', 'like', '2111%');
        })->pluck('id');

        return DB::table('transactions')
            ->selectRaw('account_id, SUM(COALESCE(credit, 0)) - SUM(COALESCE(debit, 0)) as balance')
            ->whereIn('account_id', $accountIds)
            ->whereDate('transaction_date', '<=', $this->asOfDate)
            ->groupBy('account_id')
            ->having('balance', '!=', 0)
            ->count();
    }

    public function getAssociateSavingsAccountCountProperty(): int
    {
        $accountIds = SavingsAccount::query()->where('number', 'like', '1012%')->pluck('id');

        return DB::table('transactions')
            ->selectRaw('account_id, SUM(COALESCE(credit, 0)) - SUM(COALESCE(debit, 0)) as balance')
            ->whereIn('account_id', $accountIds)
            ->whereDate('transaction_date', '<=', $this->asOfDate)
            ->groupBy('account_id')
            ->having('balance', '!=', 0)
            ->count();
    }

    public function getLaboratorySavingsAccountCountProperty(): int
    {
        $accountIds = SavingsAccount::query()->where('number', 'like', '1013%')->pluck('id');

        return DB::table('transactions')
            ->selectRaw('account_id, SUM(COALESCE(credit, 0)) - SUM(COALESCE(debit, 0)) as balance')
            ->whereIn('account_id', $accountIds)
            ->whereDate('transaction_date', '<=', $this->asOfDate)
            ->groupBy('account_id')
            ->having('balance', '!=', 0)
            ->count();
    }

    public function getSavingsAccountCountProperty(): int
    {
        return $this->regularSavingsAccountCount + $this->associateSavingsAccountCount + $this->laboratorySavingsAccountCount;
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
        return $this->data['savings_type'] ?? 'regular_savings';
    }

    public function getAccountsProperty(): Collection
    {
        $type = $this->selectedSavingsType;

        $model = match ($type) {
            'regular_savings' => SavingsAccount::class,
            'associate_savings' => SavingsAccount::class,
            'laboratory_savings' => SavingsAccount::class,
            'imprest' => ImprestAccount::class,
            'love_gift' => LoveGiftAccount::class,
            default => null,
        };

        if (! $model) {
            return collect();
        }

        $query = $model::query();

        if ($type === 'regular_savings') {
            $query->where(function ($q) {
                $q->where('number', 'like', '1011%')
                    ->orWhere('number', 'like', '2111%');
            });
        } elseif ($type === 'associate_savings') {
            $query->where('number', 'like', '1012%');
        } elseif ($type === 'laboratory_savings') {
            $query->where('number', 'like', '1013%');
        }

        $accountIds = $query->pluck('id');

        $balances = DB::table('transactions')
            ->selectRaw('account_id, SUM(COALESCE(credit, 0)) - SUM(COALESCE(debit, 0)) as balance')
            ->whereIn('account_id', $accountIds)
            ->whereDate('transaction_date', '<=', $this->asOfDate)
            ->groupBy('account_id')
            ->having('balance', '!=', 0)
            ->get()
            ->keyBy('account_id');

        $finalQuery = $model::query();

        if ($type === 'regular_savings') {
            $finalQuery->where(function ($q) {
                $q->where('number', 'like', '1011%')
                    ->orWhere('number', 'like', '2111%');
            });
        } elseif ($type === 'associate_savings') {
            $finalQuery->where('number', 'like', '1012%');
        } elseif ($type === 'laboratory_savings') {
            $finalQuery->where('number', 'like', '1013%');
        }

        return $finalQuery
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
            'regular_savings' => 'Regular Savings',
            'associate_savings' => 'Associate Savings',
            'laboratory_savings' => 'Laboratory Savings',
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
