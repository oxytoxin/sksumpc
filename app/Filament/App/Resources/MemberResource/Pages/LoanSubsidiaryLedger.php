<?php

    namespace App\Filament\App\Resources\MemberResource\Pages;

    use App\Enums\LoanTypes;
    use App\Filament\App\Resources\MemberResource;
    use App\Models\Loan;
    use App\Oxytoxin\Providers\LoansProvider;
    use Filament\Resources\Pages\Page;
    use Illuminate\Contracts\Support\Htmlable;
    use Livewire\Attributes\Computed;

    class LoanSubsidiaryLedger extends Page
    {
        protected static string $resource = MemberResource::class;

        protected static string $view = 'filament.app.resources.member-resource.pages.loan-subsidiary-ledger';

        public Loan $loan;

        public $schedule = [];

        public function mount()
        {
            $this->schedule = LoansProvider::generateAmortizationSchedule($this->loan);
        }

        #[Computed]
        public function AccruedInterest(): float
        {
            if ($this->loan->loan_type_id == LoanTypes::SPECIAL_LOAN->value) {
                return 0;
            }

            if ($this->loan->last_payment && $this->loan->last_payment->transaction_date > config('app.transaction_date')) {
                $start = $this->loan->last_payment_before_transaction_date?->transaction_date;
            } else {
                $start = $this->loan->last_payment?->transaction_date ?? $this->loan->transaction_date;
            }
            $end = config('app.transaction_date') ?? today();
            $total_days = LoansProvider::getAccruableDays($start, $end);
            $interest_due = LoansProvider::computeAccruedInterest($this->loan, $this->loan->outstanding_balance, $total_days);
            return max($interest_due, 0) + $this->loan->payments()->where('transaction_date', '<', config('app.transaction_date'))->sum('unpaid_interest');
        }

        public function getHeading(): string|Htmlable
        {
            return 'Loan Subsidiary Ledger';
        }
    }
