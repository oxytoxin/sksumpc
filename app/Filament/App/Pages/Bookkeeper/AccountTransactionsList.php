<?php

namespace App\Filament\App\Pages\Bookkeeper;

use App\Filament\App\Pages\Cashier\RequiresBookkeeperTransactionDate;
use App\Models\Account;
use App\Models\BalanceForwardedSummary;
use App\Models\Transaction;
use Auth;
use Filament\Forms\Components\Select;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Forms\Contracts\HasForms;
use Filament\Forms\Form;
use Filament\Pages\Page;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Concerns\InteractsWithTable;
use Filament\Tables\Contracts\HasTable;
use Filament\Tables\Enums\FiltersLayout;
use Filament\Tables\Table;
use Livewire\Attributes\Computed;
use Malzariey\FilamentDaterangepickerFilter\Fields\DateRangePicker;

class AccountTransactionsList extends Page implements HasForms, HasTable
{
    use InteractsWithForms, InteractsWithTable, RequiresBookkeeperTransactionDate;

    protected static ?int $navigationSort = 3;

    protected static ?string $navigationGroup = 'Bookkeeping';

    public $account;
    public $date;

    public static function shouldRegisterNavigation(): bool
    {
        return Auth::user()->can('manage bookkeeping');
    }

    protected static string $view = 'filament.app.pages.bookkeeper.account-transactions-list';

    public function mount()
    {
        $this->form->fill();
        $this->date = (config('app.transaction_date')->format('Y/m/d') ?? today()->format('Y/m/d')) . ' - ' . config('app.transaction_date')->format('Y/m/d') ?? today()->format('Y/m/d');
    }

    public function form(Form $form): Form
    {
        return $form
            ->schema([
                Select::make('account')
                    ->searchable()
                    ->options(Account::withCode()->whereDoesntHave('children', fn($q) => $q->whereNull('member_id'))->pluck('code', 'id'))
                    ->default(2)
                    ->selectablePlaceholder(false)
                    ->reactive()
                    ->afterStateUpdated(function () {
                        $this->resetTable();
                    }),
                DateRangePicker::make('date')
                    ->format('Y/m/d')
                    ->displayFormat('YYYY/MM/DD')
                    ->reactive()
                    ->afterStateUpdated(function () {
                        $this->resetTable();
                    }),
            ])
            ->columns(2);
    }

    #[Computed]
    public function ForwardedBalance()
    {
        [$date_before, $date_after] = explode(' - ', $this->date);
        $account = Account::query()
            ->withSum(['recursiveTransactions as total_debit' => fn($query) => $query->where('transaction_date', '<', $date_before)], 'debit')
            ->withSum(['recursiveTransactions as total_credit' => fn($query) => $query->where('transaction_date', '<', $date_before)], 'credit')
            ->find($this->account);
        return $account;
    }

    #[Computed]
    public function SelectedAccount()
    {
        return Account::withCode()->with('account_type')->find($this->account);
    }


    public function table(Table $table): Table
    {
        ray()->showQueries();
        return $table->query(
            Transaction::query()
                ->whereHas('account', fn($query) => $query->whereRelation('ancestorsAndSelf', 'id', $this->account))
                ->whereBetween('transaction_date', explode(' - ', $this->date))
        )
            ->columns([
                TextColumn::make('member.name'),
                TextColumn::make('payee'),
                TextColumn::make('debit'),
                TextColumn::make('credit'),
            ])
            ->filtersLayout(FiltersLayout::AboveContent)
            ->paginated(false);
    }
}
