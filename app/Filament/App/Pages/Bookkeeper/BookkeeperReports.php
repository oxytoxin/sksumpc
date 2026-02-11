<?php

    namespace App\Filament\App\Pages\Bookkeeper;

    use App\Livewire\Bookkeeper\BookkeeperLoanBalancesTab;
    use App\Livewire\Bookkeeper\BookkeeperLoanOverpaymentTab;
    use App\Livewire\Bookkeeper\BookkeeperSavingsBalanceTab;
    use Filament\Forms\Concerns\InteractsWithForms;
    use Filament\Forms\Contracts\HasForms;
    use Filament\Pages\Page;
    use Filament\Schemas\Components\Livewire;
    use Filament\Schemas\Components\Tabs;
    use Filament\Schemas\Components\Tabs\Tab;
    use Filament\Schemas\Schema;
    use Illuminate\Support\Facades\Auth;

    class BookkeeperReports extends Page implements HasForms
    {
        use InteractsWithForms;

        protected static ?int $navigationSort = 3;

        protected static string|\UnitEnum|null $navigationGroup = 'Bookkeeping';

        protected static ?string $title = 'Bookkeeper Summary Report';

        protected string $view = 'filament.app.pages.bookkeeper.bookkeeper-reports';

        public $data = [];

        public static function shouldRegisterNavigation(): bool
        {
            return Auth::user()->can('manage bookkeeping');
        }

        public function mount(): void
        {
            $this->form->fill();
        }

        public function form(Schema $schema): Schema
        {
            return $schema
                ->statePath('data')
                ->components([
                    Tabs::make('bookkeeper-summary')
                        ->activeTab(1)
                        ->persistTabInQueryString()
                        ->tabs([
                            Tab::make('Loan Overpayment')->schema([
                                Livewire::make(BookkeeperLoanOverpaymentTab::class),
                            ]),
                            Tab::make('Savings Balance')
                                ->schema([
                                    Livewire::make(BookkeeperSavingsBalanceTab::class),
                                ]),
                            Tab::make('Loan Balances')
                                ->schema([
                                    Livewire::make(BookkeeperLoanBalancesTab::class),
                                ]),
                        ]),
                ]);
        }
    }
