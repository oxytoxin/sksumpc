<?php

namespace App\Filament\App\Pages\Bookkeeper;

use App\Models\Account;
use App\Models\Transaction;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Forms\Contracts\HasForms;
use Filament\Pages\Page;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Concerns\InteractsWithTable;
use Filament\Tables\Contracts\HasTable;
use Filament\Tables\Table;
use Livewire\Attributes\Computed;
use stdClass;

class TransactionsList extends Page implements HasForms, HasTable
{
    use InteractsWithForms, InteractsWithTable;

    protected static ?int $navigationSort = 3;

    protected static ?string $navigationGroup = 'Bookkeeping';

    public static function shouldRegisterNavigation(): bool
    {
        return auth()->user()->can('manage bookkeeping');
    }

    protected static string $view = 'filament.app.pages.bookkeeper.transactions-list';

    public ?int $transaction_type;

    public ?int $account_id;

    public ?int $payment_mode;

    public ?int $month;

    public ?int $year;

    #[Computed]
    public function account()
    {
        return Account::find($this->account_id);
    }

    public function mount()
    {
        $this->transaction_type = request()->integer('transaction_type');
        $this->account_id = request()->integer('account_id');
        $this->payment_mode = request()->integer('payment_mode');
        $this->month = request()->integer('month');
        $this->year = request()->integer('year');
    }

    public function table(Table $table): Table
    {
        return $table
            ->query(
                Transaction::query()
                    ->when($this->account_id, fn ($q) => $q->where('account_id', $this->account_id))
                    ->when($this->transaction_type, fn ($q) => $q->where('transaction_type_id', $this->transaction_type))
                    ->when($this->payment_mode == 1, fn ($q) => $q->whereNotNull('debit'))
                    ->when($this->payment_mode == -1, fn ($q) => $q->whereNotNull('credit'))
                    ->when($this->month, fn ($q) => $q->whereMonth('transaction_date', $this->month))
                    ->when($this->year, fn ($q) => $q->whereYear('transaction_date', $this->year))
            )
            ->columns([
                TextColumn::make('Number')->state(
                    static function (HasTable $livewire, stdClass $rowLoop): string {
                        return (string) (
                            $rowLoop->iteration +
                            ($livewire->getTableRecordsPerPage() * (
                                $livewire->getTablePage() - 1
                            ))
                        );
                    }
                ),
                TextColumn::make('transaction_date')->date('F d, Y'),
                TextColumn::make('reference_number'),
                TextColumn::make('account.name')->label('Account Name'),
                TextColumn::make('account.number')->label('Account Number'),
                TextColumn::make('debit'),
                TextColumn::make('credit'),
                TextColumn::make('transaction_type.name'),
            ]);
    }
}