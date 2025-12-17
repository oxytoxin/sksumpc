<?php

    namespace App\Filament\App\Pages;

    use App\Enums\CashEquivalentsTag;
    use App\Models\Loan;
    use App\Models\LoanPayment;
    use App\Models\Member;
    use App\Models\Transaction;
    use Filament\Forms\Components\Select;
    use Filament\Forms\Concerns\InteractsWithForms;
    use Filament\Forms\Contracts\HasForms;
    use Filament\Pages\Page;
    use Filament\Schemas\Concerns\InteractsWithSchemas;
    use Filament\Schemas\Contracts\HasSchemas;
    use Filament\Schemas\Schema;
    use Livewire\Attributes\Computed;

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
        public function loan_payment_transactions()
        {
            return Transaction::query()
                ->when($this->loan_payment->principal_payment, fn($query) => $query->where(function ($query) {
                    $query
                        ->where('reference_number', $this->loan_payment->reference_number)
                        ->where('member_id', $this->loan_payment->member_id)
                        ->where('transaction_date', $this->loan_payment->transaction_date)
                        ->where('credit', $this->loan_payment->principal_payment)
                        ->where('remarks', 'Member Loan Payment Principal');
                }))
                ->when($this->loan_payment->interest_payment, fn($query) => $query->orWhere(function ($query) {
                    $query
                        ->where('reference_number', $this->loan_payment->reference_number)
                        ->where('member_id', $this->loan_payment->member_id)
                        ->where('transaction_date', $this->loan_payment->transaction_date)
                        ->where('credit', $this->loan_payment->interest_payment)
                        ->where('remarks', 'Member Loan Payment Interest');
                }))
                ->orWhere(function ($query) {
                    $query
                        ->where('reference_number', $this->loan_payment->reference_number)
                        ->where('member_id', $this->loan_payment->member_id)
                        ->where('transaction_date', $this->loan_payment->transaction_date)
                        ->whereIn('account_id', [2, 4])
                        ->whereNull('credit')
                        ->where('debit', $this->loan_payment->amount);
                })
                ->get();
        }

    }
