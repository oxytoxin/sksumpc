<?php

    namespace App\Filament\App\Pages;

    use App\Models\Loan;
    use App\Models\LoanPayment;
    use App\Models\Member;
    use Filament\Actions\Action;
    use Filament\Forms\Components\Select;
    use Filament\Forms\Concerns\InteractsWithForms;
    use Filament\Forms\Contracts\HasForms;
    use Filament\Notifications\Notification;
    use Filament\Pages\Page;
    use Filament\Schemas\Concerns\InteractsWithSchemas;
    use Filament\Schemas\Contracts\HasSchemas;
    use Filament\Schemas\Schema;
    use Filament\Tables\Concerns\InteractsWithTable;
    use Filament\Tables\Contracts\HasTable;
    use Illuminate\Support\Facades\DB;
    use Livewire\Attributes\Computed;

    class Accounting extends Page implements HasForms
    {
        use InteractsWithForms;

        protected static string|\BackedEnum|null $navigationIcon = 'icon-accounting';

        protected string $view = 'filament.app.pages.accounting';

        protected static ?int $navigationSort = 11;

        public $data = [];
        public $loan_payment_id;

        public function form(Schema $schema): Schema
        {
            return $schema->reactive()
                ->components([
                    Select::make('member_id')
                        ->label('Member')
                        ->options(fn() => Member::pluck('full_name', 'id'))
                        ->afterStateUpdated(fn($set, $state) => $set('loan_id', null))
                        ->searchable(),
                    Select::make('loan_id')
                        ->afterStateUpdated(fn($set, $state) => $this->reset('loan_payment_id'))
                        ->options(fn($get) => Loan::whereMemberId($get('member_id'))->pluck('reference_number', 'id')),
                    Action::make('delete_payment')
                        ->requiresConfirmation()
                        ->color('danger')
                        ->action(fn() => $this->deletePayment())
                        ->disabled(fn() => !$this->loan_payment_id),
                ])->statePath('data');
        }

        public static function shouldRegisterNavigation(): bool
        {
            return false;
        }

        #[Computed]
        public function loan_payments()
        {
            $loan = Loan::find($this->data['loan_id']);
            return $loan?->payments ?? collect();
        }

        #[Computed]
        public function loan_payment_transactions()
        {
            $loan_payment = LoanPayment::find($this->loan_payment_id);
            return $loan_payment?->getTransactions() ?? collect();
        }

        public function selectPayment($loan_payment_id)
        {
            $this->loan_payment_id = $loan_payment_id;
        }

        public function deletePayment()
        {
            $loan_payment = LoanPayment::find($this->loan_payment_id);
            if ($loan_payment) {
                $transactions = $loan_payment->getTransactions();
                foreach ($transactions as $transaction) {
                    $transaction->delete();
                }
                $loan = $loan_payment->loan;
//                $loan->outstanding_balance += $loan_payment->principal_payment; //No need due to database trigger?
                $loan->save();
                $loan_payment->delete();
                $this->reset('loan_payment_id');
                Notification::make()->title('Loan Payment Deleted')->success()->send();
            }
        }

        public function mount(): void
        {
            $this->form->fill();
        }
    }
