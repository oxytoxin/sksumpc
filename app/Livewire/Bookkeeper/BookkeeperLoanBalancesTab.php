<?php

namespace App\Livewire\Bookkeeper;

use App\Models\Loan;
use App\Models\LoanType;
use Carbon\CarbonImmutable;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Select;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Forms\Contracts\HasForms;
use Filament\Schemas\Schema;
use Illuminate\Database\Eloquent\Collection;
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
        return $schema
            ->statePath('data')
            ->components([
                DatePicker::make('to_date')
                    ->label('As Of Date')
                    ->live()
                    ->default(config('app.transaction_date'))
                    ->native(false)
                    ->displayFormat('m/d/Y')
                    ->required(),
                Select::make('loan_type')
                    ->label('Loan Type')
                    ->options(LoanType::query()->pluck('name', 'id'))
                    ->live()
                    ->searchable()
                    ->placeholder('All Loan Types'),
            ]);
    }

    public function getAsOfDateProperty(): CarbonImmutable
    {
        return CarbonImmutable::parse($this->data['to_date'] ?? today());
    }

    public function getTotalOutstandingLoansProperty(): float
    {
        return Loan::wherePosted(true)
            ->where('outstanding_balance', '>', 0)
            ->sum('outstanding_balance');
    }

    public function getLoanBalancesByTypeProperty(): Collection
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

    public function getTotalLoanCountProperty(): int
    {
        return $this->loanBalancesByType->sum('loan_count');
    }

    public function getSelectedLoanTypeIdProperty(): ?int
    {
        return $this->data['loan_type'] ?? null;
    }

    public function getLoansProperty(): Collection
    {
        $query = Loan::query()
            ->with(['member', 'loan_type'])
            ->wherePosted(true)
            ->where('outstanding_balance', '>', 0)
            ->orderBy('reference_number');

        if ($this->selectedLoanTypeId) {
            $query->where('loan_type_id', $this->selectedLoanTypeId);
        }

        return $query->get();
    }

    public function getLoanTypeLabelProperty(): string
    {
        if (! $this->selectedLoanTypeId) {
            return 'All Loans';
        }

        return LoanType::find($this->selectedLoanTypeId)?->name ?? 'Loans';
    }

    public function render()
    {
        return view('livewire.bookkeeper.bookkeeper-loan-balances-tab');
    }
}
