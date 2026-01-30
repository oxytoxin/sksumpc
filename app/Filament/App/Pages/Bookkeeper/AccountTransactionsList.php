<?php

    namespace App\Filament\App\Pages\Bookkeeper;

    use App\Filament\App\Pages\Cashier\RequiresBookkeeperTransactionDate;
    use App\Models\Account;
    use App\Models\Transaction;
    use Auth;
    use Filament\Forms\Components\Select;
    use Filament\Forms\Concerns\InteractsWithForms;
    use Filament\Forms\Contracts\HasForms;
    use Filament\Pages\Page;
    use Filament\Schemas\Schema;
    use Livewire\Attributes\Computed;
    use Malzariey\FilamentDaterangepickerFilter\Fields\DateRangePicker;

    class AccountTransactionsList extends Page implements HasForms
    {
        use InteractsWithForms, RequiresBookkeeperTransactionDate;

        protected static ?int $navigationSort = 3;

        protected static string|\UnitEnum|null $navigationGroup = 'Bookkeeping';

        public $account;

        public $date;

        public static function shouldRegisterNavigation(): bool
        {
            return Auth::user()->can('manage bookkeeping');
        }

        protected string $view = 'filament.app.pages.bookkeeper.account-transactions-list';

        public function mount()
        {
            $this->form->fill();
            $this->date = (config('app.transaction_date')->format('Y/m/d') ?? today()->format('Y/m/d')).' - '.config('app.transaction_date')->format('Y/m/d') ?? today()->format('Y/m/d');
        }

        public function form(Schema $schema): Schema
        {
            return $schema
                ->components([
                    Select::make('account')
                        ->options(fn() => $this->account ?
                            Account::withCode()->pluck('code', 'id') : []
                        )
                        ->searchable()
                        ->reactive()
                        ->default(2),
                    DateRangePicker::make('date')
                        ->format('Y/m/d')
                        ->reactive()
                        ->displayFormat('YYYY/MM/DD'),
                ])
                ->columns(2);
        }

        #[Computed]
        public function ForwardedBalance()
        {
            [$date_before, $date_after] = explode(' - ', $this->date);
            $date_before = str_replace('/', '-', $date_before);
            $account = Account::find($this->account);
            $accountIds = $account->descendantsAndSelf()->pluck('id');

            $totals = Transaction::query()
                ->whereIn('account_id', $accountIds)
                ->where('transaction_date', '<', $date_before)
                ->selectRaw('SUM(COALESCE(debit, 0)) as total_debit, SUM(COALESCE(credit, 0)) as total_credit')
                ->first();

            return (object) [
                'total_debit' => (float) ($totals->total_debit ?? 0),
                'total_credit' => (float) ($totals->total_credit ?? 0),
            ];
        }

        #[Computed]
        public function TableTotals()
        {
            [$date_from, $date_to] = explode(' - ', $this->date);
            $date_from = str_replace('/', '-', $date_from);
            $date_to = str_replace('/', '-', $date_to);
            $account = Account::find($this->account);
            $accountIds = $account->descendantsAndSelf()->pluck('id');

            $totals = Transaction::query()
                ->whereIn('account_id', $accountIds)
                ->whereBetween('transaction_date', [$date_from, $date_to])
                ->selectRaw('SUM(COALESCE(debit, 0)) as total_debit, SUM(COALESCE(credit, 0)) as total_credit')
                ->first();

            return (object) [
                'total_debit' => (float) ($totals->total_debit ?? 0),
                'total_credit' => (float) ($totals->total_credit ?? 0),
            ];
        }

        #[Computed]
        public function SelectedAccount()
        {
            return Account::withCode()->with('account_type')->find($this->account);
        }

        #[Computed]
        public function transactions()
        {
            [$date_from, $date_to] = explode(' - ', $this->date);
            $date_from = str_replace('/', '-', $date_from);
            $date_to = str_replace('/', '-', $date_to);
            $account = Account::find($this->account);
            $accountIds = $account->descendantsAndSelf()->pluck('id');
            $debitOperator = $this->selected_account?->account_type->debit_operator ?? 1;
            $creditOperator = $this->selected_account?->account_type->credit_operator ?? 1;

            return Transaction::query()
                ->whereIn('account_id', $accountIds)
                ->whereBetween('transaction_date', [$date_from, $date_to])
                ->with(['account:id,number,name'])
                ->orderBy('transaction_date')
                ->orderBy('id')
                ->select([
                    'transactions.*',
                    \DB::raw('(
                    SUM(COALESCE(debit, 0) * '.$debitOperator.' + COALESCE(credit, 0) * '.$creditOperator.')
                    OVER (ORDER BY transaction_date, id ROWS BETWEEN UNBOUNDED PRECEDING AND CURRENT ROW)
                    + (
                        SELECT COALESCE(SUM(t2.debit), 0) * '.$debitOperator.' + COALESCE(SUM(t2.credit), 0) * '.$creditOperator.'
                        FROM transactions t2
                        WHERE t2.account_id IN ('.implode(',', $accountIds->toArray()).')
                        AND t2.transaction_date < "'.$date_from.'"
                    )
                ) as running_balance'),
                ])
                ->lazy()
                ->each(fn($t) => $t->running_balance = $t->running_balance ?? 0);
        }
    }
