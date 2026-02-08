<?php

namespace App\Livewire\Bookkeeper;

use App\Models\Loan;
use App\Models\LoanPayment;
use Carbon\CarbonImmutable;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Forms\Contracts\HasForms;
use Filament\Schemas\Schema;
use Illuminate\Database\Eloquent\Collection;
use Livewire\Attributes\Computed;
use Livewire\Component;

class BookkeeperLoanOverpaymentTab extends Component implements HasForms
{
    use InteractsWithForms;

    public $data = [];

    public function mount(): void
    {
        $this->form->fill();
    }

    public function form(Schema $schema): Schema
    {
        return $schema->components([
            DatePicker::make('to_date')
                ->label('As Of Date')
                ->live()
                ->default(today())
                ->native(false)
                ->displayFormat('m/d/Y')
                ->required(),
        ]);
    }

    #[Computed]
    public function asOfDate(): CarbonImmutable
    {
        return CarbonImmutable::parse($this->data['to_date'] ?? today());
    }

    #[Computed]
    public function loanOverpaymentsExcess(): Collection
    {
        return LoanPayment::query()
            ->selectRaw('loan_id,
                SUM(amount) as total_paid,
                SUM(principal_payment) as total_principal,
                SUM(interest_payment) as total_interest,
                SUM(surcharge_payment) as total_surcharge,
                (SUM(amount) - (SUM(principal_payment) + SUM(interest_payment) + SUM(surcharge_payment))) as overpayment')
            ->having('overpayment', '>', 0)
            ->with('loan.member', 'loan.loan_type')
            ->groupBy('loan_id')
            ->get();
    }

    #[Computed]
    public function loanOverpaymentsNegative(): Collection
    {
        return Loan::where('outstanding_balance', '<', 0)
            ->wherePosted(true)
            ->with('member', 'loan_type')
            ->get();
    }

    #[Computed]
    public function totalOverpaymentsAmount(): float
    {
        return $this->loanOverpaymentsExcess->sum('overpayment') + $this->loanOverpaymentsNegative->sum(fn ($loan) => abs($loan->outstanding_balance));
    }

    public function render()
    {
        return view('livewire.bookkeeper.bookkeeper-loan-overpayment-tab');
    }
}
