<?php

    namespace App\Filament\App\Pages;

    use App\Actions\Loans\RecomputeLoan;
    use App\Enums\CashEquivalentsTag;
    use App\Models\Loan;
    use App\Models\LoanPayment;
    use App\Models\Member;
    use App\Models\Transaction;
    use App\Oxytoxin\Providers\LoansProvider;
    use Filament\Actions\Action;
    use Filament\Forms\Components\Select;
    use Filament\Forms\Concerns\InteractsWithForms;
    use Filament\Forms\Contracts\HasForms;
    use Filament\Pages\Page;
    use Filament\Schemas\Concerns\InteractsWithSchemas;
    use Filament\Schemas\Contracts\HasSchemas;
    use Filament\Schemas\Schema;
    use Illuminate\Support\Facades\DB;
    use Livewire\Attributes\Computed;
    use stdClass;

    class Casa extends Page implements HasSchemas
    {

        use InteractsWithSchemas;

        protected static string|\BackedEnum|null $navigationIcon = 'icon-casa';

        protected string $view = 'filament.app.pages.casa';

        protected static ?int $navigationSort = 13;

        public array $data = [];

        public ?LoanPayment $loan_payment;

        public static function shouldRegisterNavigation(): bool
        {
            return false;
        }

        public function form(Schema $schema): Schema
        {
            return $schema
                ->reactive()
                ->components([
                    Select::make('member_id')
                        ->label('Member')
                        ->options(fn() => Member::pluck('full_name', 'id'))
                        ->afterStateUpdated(fn($set, $state) => $set('loan_id', null))
                        ->searchable(),
                    Select::make('loan_id')
                        ->afterStateUpdated(fn($set, $state) => $this->loan_payment = null)
                        ->options(fn($get) => Loan::whereMemberId($get('member_id'))->pluck('reference_number', 'id')),
                    Action::make('recompute')
                        ->requiresConfirmation()
                        ->action(fn() => $this->recompute()),
                ])->statePath('data');
        }

        public function mount()
        {
            $this->form->fill([
                'member_id' => 147,
                'loan_id' => 5,
            ]);
        }

        public function selectLoanPayment(LoanPayment $loanPayment)
        {
            $this->loan_payment = $loanPayment;
        }

        #[Computed]
        public function simulated_payments()
        {
            $loan = Loan::find($this->data['loan_id']);
            if ($loan) {
                return $this->getSimulatedPayments($loan);
            }
            return [];
        }

        #[Computed]
        public function loan_payment_transactions()
        {
            $loan = Loan::find($this->data['loan_id']);
            $transactions = collect();
            if ($loan) {
                foreach ($loan->payments as $loan_payment) {
                    $results = $loan_payment->getTransactions();
                    $results->each(fn($result) => $transactions->push($result));
                }
            }
            return $transactions;
        }

        public function recompute(): void
        {
            $loan = Loan::find($this->data['loan_id']);
            RecomputeLoan::handle($loan);
        }

        private function getSimulatedPayments(Loan $loan): array
        {
            return RecomputeLoan::getSimulatedPayments($loan);
        }


    }
