<?php

    namespace App\Filament\App\Pages;

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
                    $results = $this->getLoanPaymentTransactions($loan_payment);
                    $results->each(fn($result) => $transactions->push($result));
                }
            }
            return $transactions;
        }

        public function recompute(): void
        {
            $loan = Loan::find($this->data['loan_id']);
            $simulated_payments = $this->getSimulatedPayments($loan);
            $actual_payments = $loan->payments;
            foreach ($actual_payments as $key => $actual_payment) {
                if ($actual_payment->reference_number == '#BALANCEFORWARDED') {
                    continue;
                }

                $principal_payment_transaction = Transaction::query()
                    ->where('reference_number', $actual_payment->reference_number)
                    ->where('member_id', $actual_payment->member_id)
                    ->where('transaction_date', $actual_payment->transaction_date)
                    ->where('credit', $actual_payment->principal_payment)
                    ->where('remarks', 'Member Loan Payment Principal')
                    ->first();
                $interest_payment_transaction = Transaction::query()
                    ->where('reference_number', $actual_payment->reference_number)
                    ->where('member_id', $actual_payment->member_id)
                    ->where('transaction_date', $actual_payment->transaction_date)
                    ->where('credit', $actual_payment->interest_payment)
                    ->where('remarks', 'Member Loan Payment Interest')
                    ->first();
                $cash_transaction = Transaction::query()
                    ->where('reference_number', $actual_payment->reference_number)
                    ->where('member_id', $actual_payment->member_id)
                    ->where('transaction_date', $actual_payment->transaction_date)
                    ->whereIn('account_id', [2, 4])
                    ->whereNull('credit')
                    ->where('debit', $actual_payment->amount)
                    ->first();


                $actual_payment->principal_payment = $simulated_payments[$key]->principal_payment;
                $actual_payment->interest_payment = $simulated_payments[$key]->interest_payment;
                $loan->outstanding_balance = $simulated_payments[$key]->balance;


                if ($principal_payment_transaction) {
                    $principal_payment_transaction->credit = $actual_payment->principal_payment;
                    $principal_payment_transaction->save();
                }

                if ($interest_payment_transaction) {
                    $interest_payment_transaction->credit = $actual_payment->interest_payment;
                    $interest_payment_transaction->save();
                }

                if ($cash_transaction) {
                    $cash_transaction->debit = $actual_payment->amount;
                    $cash_transaction->save();
                }

                $actual_payment->save();
            }
            $loan->save();
        }

        private function getSimulatedPayments(Loan $loan): array
        {
            $payments = [];
            $balance = $loan->gross_amount;
            $previous_date = $loan->transaction_date;
            foreach ($loan->payments as $loan_payment) {
                if ($loan_payment->reference_number == '#BALANCEFORWARDED') {
                    $interest = 0;
                } else {
                    $interest = LoansProvider::computeAccruedInterestFromDates($loan, $balance, $previous_date, $loan_payment->transaction_date);
                }
                $principal = $loan_payment->amount - $interest;
                $balance -= $principal;
                $previous_date = $loan_payment->transaction_date;
                $payment = new LoanPayment([
                    'id' => $loan_payment->id,
                    'amount' => $loan_payment->amount,
                    'reference_number' => $loan_payment->reference_number,
                    'principal_payment' => $principal,
                    'interest_payment' => $interest,
                    'balance' => $balance,
                    'transaction_date' => $loan_payment->transaction_date,
                ]);
                $payments[] = $payment;
            }
            return $payments;
        }

        private function getLoanPaymentTransactions(LoanPayment $loan_payment): \Illuminate\Database\Eloquent\Collection
        {
            return Transaction::query()
                ->when($loan_payment->principal_payment, fn($query) => $query->where(function ($query) use ($loan_payment) {
                    $query
                        ->where('reference_number', $loan_payment->reference_number)
                        ->where('member_id', $loan_payment->member_id)
                        ->where('transaction_date', $loan_payment->transaction_date)
                        ->where('credit', $loan_payment->principal_payment)
                        ->where('remarks', 'Member Loan Payment Principal');
                }))
                ->when($loan_payment->interest_payment, fn($query) => $query->orWhere(function ($query) use ($loan_payment) {
                    $query
                        ->where('reference_number', $loan_payment->reference_number)
                        ->where('member_id', $loan_payment->member_id)
                        ->where('transaction_date', $loan_payment->transaction_date)
                        ->where('credit', $loan_payment->interest_payment)
                        ->where('remarks', 'Member Loan Payment Interest');
                }))
                ->orWhere(function ($query) use ($loan_payment) {
                    $query
                        ->where('reference_number', $loan_payment->reference_number)
                        ->where('member_id', $loan_payment->member_id)
                        ->where('transaction_date', $loan_payment->transaction_date)
                        ->whereIn('account_id', [2, 4])
                        ->whereNull('credit')
                        ->where('debit', $loan_payment->amount);
                })
                ->get();
        }

    }
