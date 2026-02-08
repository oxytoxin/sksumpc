<?php

namespace App\Livewire\Bookkeeper;

use App\Models\Loan;
use Carbon\CarbonImmutable;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Forms\Contracts\HasForms;
use Filament\Schemas\Schema;
use Illuminate\Database\Eloquent\Collection;
use Livewire\Attributes\Computed;
use Livewire\Component;

class BookkeeperLoanBalancesTab extends Component implements HasForms
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
    public function totalOutstandingLoans(): float
    {
        return Loan::wherePosted(true)
            ->where('outstanding_balance', '>', 0)
            ->sum('outstanding_balance');
    }

    #[Computed]
    public function loanBalancesByType(): Collection
    {
        return Loan::query()
            ->selectRaw('loan_types.name as loan_type_name,
                COUNT(loans.id) as loan_count,
                SUM(loans.outstanding_balance) as total_outstanding')
            ->join('loan_types', 'loans.loan_type_id', '=', 'loan_types.id')
            ->wherePosted(true)
            ->where('outstanding_balance', '>', 0)
            ->groupBy('loan_type_id', 'loan_types.name')
            ->orderBy('total_outstanding', 'desc')
            ->get();
    }

    #[Computed]
    public function totalLoanCount(): int
    {
        return $this->loanBalancesByType->sum('loan_count');
    }

    public function render()
    {
        return view('livewire.bookkeeper.bookkeeper-loan-balances-tab');
    }
}
