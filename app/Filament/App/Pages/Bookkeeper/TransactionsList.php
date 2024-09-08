<?php

namespace App\Filament\App\Pages\Bookkeeper;

use App\Models\Account;
use App\Models\Transaction;
use Carbon\Carbon;
use Carbon\CarbonImmutable;
use Filament\Actions\Action;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Forms\Contracts\HasForms;
use Filament\Pages\Page;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Concerns\InteractsWithTable;
use Filament\Tables\Contracts\HasTable;
use Filament\Tables\Enums\FiltersLayout;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;
use Livewire\Attributes\Computed;
use Malzariey\FilamentDaterangepickerFilter\Filters\DateRangeFilter;
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

    public $date_range;

    #[Computed]
    public function account()
    {
        return Account::find($this->account_id);
    }

    protected function getHeaderActions(): array
    {
        return [
            Action::make('account_balance')
                ->url(AccountBalanceReport::getUrl())
        ];
    }

    public function mount()
    {
        $this->transaction_type = request()->integer('transaction_type');
        $this->account_id = request()->integer('account_id');
        $this->payment_mode = request()->integer('payment_mode');
        $month = request()->integer('month');
        $year = request()->integer('year');
        if ($month && $year) {
            $date = CarbonImmutable::create(month: $month, year: $year);
            $this->date_range = $date->format('m/d/Y') . ' - ' . $date->endOfMonth()->format('m/d/Y');
        } else {
            $this->date_range = (config('app.transaction_date')->format('m/d/Y') ?? today()->format('m/d/Y')) . ' - ' . config('app.transaction_date')->format('m/d/Y') ?? today()->format('m/d/Y');
        }
        data_set($this, 'tableFilters.transaction_date.transaction_date', $this->date_range);
    }

    public function table(Table $table): Table
    {
        return $table
            ->query(
                Transaction::query()
                    ->when($this->account_id, fn($q) => $q->whereIn('account_id', Account::find($this->account_id)->descendantsAndSelf()->pluck('id')))
                    ->when($this->transaction_type, fn($q) => $q->where('transaction_type_id', $this->transaction_type))
                    ->when($this->payment_mode == 1, fn($q) => $q->whereNotNull('debit'))
                    ->when($this->payment_mode == -1, fn($q) => $q->whereNotNull('credit'))
            )
            ->filters([
                DateRangeFilter::make('transaction_date')
                    ->format('m/d/Y')
                    ->displayFormat('MM/DD/YYYY'),
                SelectFilter::make('transaction_type')
                    ->relationship('transaction_type', 'name')
                    ->default($this->transaction_type)
            ])
            ->filtersLayout(FiltersLayout::AboveContent)
            ->columns([
                TextColumn::make('#')->state(
                    static function (HasTable $livewire, stdClass $rowLoop): string {
                        return (string)(
                            $rowLoop->iteration +
                            ($livewire->getTableRecordsPerPage() * (
                                    $livewire->getTablePage() - 1
                                ))
                        );
                    }
                ),
                TextColumn::make('transaction_date')->date('F d, Y'),
                TextColumn::make('reference_number'),
                TextColumn::make('member.full_name')->label('Member'),
                TextColumn::make('account.name')->label('Account Name'),
                TextColumn::make('account.number')->label('Account Number'),
                TextColumn::make('debit')->formatStateUsing(fn($state) => renumber_format($state, 4)),
                TextColumn::make('credit')->formatStateUsing(fn($state) => renumber_format($state, 4)),
                TextColumn::make('running_balance')->formatStateUsing(fn($record) => 2),
                TextColumn::make('transaction_type.name'),
            ]);
    }
}
