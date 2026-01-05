<?php

    namespace App\Filament\App\Pages\Bookkeeper;

    use App\Models\CapitalSubscriptionBilling;
    use App\Models\CashCollectibleBilling;
    use App\Models\Loan;
    use App\Models\LoanBilling;
    use App\Models\MsoBilling;
    use App\Models\RevolvingFund;
    use App\Models\User;
    use Filament\Infolists\Components\TextEntry;
    use Filament\Schemas\Schema;
    use Filament\Schemas\Components\Actions;
    use Filament\Actions\Action;
    use App\Models\SystemConfiguration;
    use App\Models\TransactionDateHistory;
    use Carbon\Carbon;
    use DB;
    use Filament\Actions\Concerns\InteractsWithActions;
    use Filament\Actions\Contracts\HasActions;
    use Filament\Forms\Components\DatePicker;
    use Filament\Forms\Components\Placeholder;
    use Filament\Forms\Concerns\InteractsWithForms;
    use Filament\Forms\Contracts\HasForms;
    use Filament\Notifications\Notification;
    use Filament\Pages\Page;
    use Illuminate\Database\Eloquent\Collection;
    use Illuminate\Support\Collection as SupportCollection;
    use Livewire\Attributes\Computed;

    class TransactionDateManager extends Page implements HasActions, HasForms
    {
        use InteractsWithActions, InteractsWithForms;

        protected static ?int $navigationSort = 1;

        protected static string|\UnitEnum|null $navigationGroup = 'Bookkeeping';

        protected string $view = 'filament.app.pages.bookkeeper.transaction-date-manager';

        public $transaction_date;

        public static function shouldRegisterNavigation(): bool
        {
            return auth()->user()->can('manage bookkeeping');
        }

        public function mount(): void
        {
            $this->form->fill();
        }

        #[Computed]
        public function loans_for_voucher(): Collection
        {
            return Loan::wherePosted(false)->with('member')->get();
        }

        #[Computed]
        public function unposted_billings(): Collection
        {
            $cbu = CapitalSubscriptionBilling::where('posted', false)->whereNotNull('or_number')->select(['name', 'reference_number', 'billable_date']);
            $mso = MsoBilling::where('posted', false)->whereNotNull('or_number')->select(['name', 'reference_number', 'billable_date']);
            $loans = LoanBilling::where('posted', false)->whereNotNull('or_number')->select(['name', 'reference_number', 'billable_date']);
            $cash_collections = CashCollectibleBilling::where('posted', false)->whereNotNull('or_number')->select(['name', 'reference_number', 'billable_date']);
            return $cbu->union($mso)->union($loans)->union($cash_collections)->get();
        }

        #[Computed]
        public function revolving_fund_cleared(): bool
        {
            return RevolvingFund::doesntExist();
        }

        #[Computed]
        public function active_users(): SupportCollection
        {
            return DB::table('sessions')
                ->where('last_activity', '>=', now()->subMinutes(config('session.lifetime'))->timestamp)
                ->whereNot('user_id', auth()->id())
                ->get()
                ->map(fn($session) => User::find($session->user_id));
        }

        public function form(Schema $schema): Schema
        {
            return $schema->components([
                TextEntry::make('current_transaction_date')
                    ->state(fn() => config('app.transaction_date')?->format('m/d/Y')),
                TextEntry::make('note')
                    ->state(fn($get) => config('app.transaction_date') ? 'All transactions date will be set to '.config('app.transaction_date')->format('m/d/Y') : "No transaction date set for today's transactions."),
                DatePicker::make('transaction_date')
                    ->native(false)
                    ->unique('transaction_date_histories', 'date')
                    ->validationMessages([
                        'unique' => 'This date has already been used in the past.',
                    ])
                    ->default(config('app.transaction_date') ?? today())
                    ->required()
                    ->live(),
                Actions::make([
                    Action::make('set')
                        ->label('Set Date')
                        ->action(function () {
                            $this->form->validate();
                            DB::beginTransaction();
                            TransactionDateHistory::query()->update([
                                'is_current' => false,
                            ]);
                            TransactionDateHistory::create([
                                'date' => $this->transaction_date,
                                'is_current' => true,
                            ]);
                            DB::commit();
                            Notification::make()->title('Transaction date set!')->success()->send();
                        })
                        ->color('success'),
                    Action::make('clear')
                        ->label('Close Date')
                        ->action(function () {
                            TransactionDateHistory::query()->update([
                                'is_current' => false,
                            ]);
                            SystemConfiguration::config('Transaction Date')?->delete();
                            $this->reset();
                            Notification::make()->title('Transaction date cleared!')->success()->send();
                        })
                        ->disabled(fn() => !$this->revolving_fund_cleared || $this->active_users->isNotEmpty() || $this->loans_for_voucher->isNotEmpty() || $this->unposted_billings->isNotEmpty())
                        ->requiresConfirmation()
                        ->color('danger'),
                ]),
            ]);
        }
    }
