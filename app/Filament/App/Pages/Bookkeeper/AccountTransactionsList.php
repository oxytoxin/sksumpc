<?php

namespace App\Filament\App\Pages\Bookkeeper;

use App\Filament\App\Pages\Cashier\RequiresBookkeeperTransactionDate;
use App\Models\Account;
use App\Models\BalanceForwardedSummary;
use App\Models\Transaction;
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

class AccountTransactionsList extends Page implements HasForms, HasTable
{
    use InteractsWithForms, InteractsWithTable, RequiresBookkeeperTransactionDate;

    protected static ?int $navigationSort = 3;

    protected static ?string $navigationGroup = 'Bookkeeping';

    public $account;

    public static function shouldRegisterNavigation(): bool
    {
        return auth()->user()->can('manage bookkeeping');
    }

    protected static string $view = 'filament.app.pages.bookkeeper.account-transactions-list';

    public function mount()
    {
        $this->form->fill();
    }

    public function form(Form $form): Form
    {
        return $form
            ->schema([
                Select::make('account')
                    ->searchable()
                    ->options(Account::withCode()->whereDoesntHave('children', fn ($q) => $q->whereNull('member_id'))->pluck('code', 'id'))
                    ->default(2)
                    ->selectablePlaceholder(false)
                    ->reactive()
                    ->afterStateUpdated(function () {
                        $this->resetTable();
                    }),
            ]);
    }

    #[Computed]
    public function ForwardedBalance()
    {
        $latest_balance_forwarded_summary = BalanceForwardedSummary::latest()->first();
        $balance_forwarded_summary_entry = $latest_balance_forwarded_summary?->balance_forwarded_entries()->whereAccountId($this->account)->first();

        return $balance_forwarded_summary_entry;
    }

    #[Computed]
    public function SelectedAccount()
    {
        return Account::withCode()->with('account_type')->find($this->account);
    }

    public function table(Table $table): Table
    {
        return $table->query(
            Transaction::query()
                ->whereHas('account', fn ($query) => $query->whereRelation('ancestorsAndSelf', 'id', $this->account))
                ->whereYear('transaction_date', config('app.transaction_date')?->year)
        )
            ->content(view('filament.app.pages.bookkeeper.account-transactions-list-table'))
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
