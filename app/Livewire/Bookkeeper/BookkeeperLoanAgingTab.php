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

    class BookkeeperLoanAgingTab extends Component implements HasForms
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

        public function getSelectedLoanTypeIdProperty(): ?int
        {
            return filled($this->data['loan_type']) ? $this->data['loan_type'] : null;
        }

        public function getLoansQuery()
        {
            $query = Loan::query()
                ->with(['member', 'loan_type'])
                ->wherePosted(true)
                ->where('outstanding_balance', '>', 0)
                ->where('maturity_date', '<', $this->asOfDate)
                ->orderBy('reference_number');

            if ($this->selectedLoanTypeId) {
                $query->where('loan_type_id', $this->selectedLoanTypeId);
            }

            return $query;
        }

        public function getLoansProperty(): Collection
        {
            return $this->getLoansQuery()->get();
        }

        public function getAgingBucketsProperty(): array
        {
            $loans = $this->loans;
            $asOfDate = $this->asOfDate;

            $buckets = [
                '1-30' => ['name' => '1-30 Days', 'days' => [1, 30], 'loans' => collect(), 'total' => 0, 'count' => 0],
                '31-60' => ['name' => '31-60 Days', 'days' => [31, 60], 'loans' => collect(), 'total' => 0, 'count' => 0],
                '61-90' => ['name' => '61-90 Days', 'days' => [61, 90], 'loans' => collect(), 'total' => 0, 'count' => 0],
                '91-120' => ['name' => '91-120 Days', 'days' => [91, 120], 'loans' => collect(), 'total' => 0, 'count' => 0],
                '121+' => ['name' => '121+ Days', 'days' => [121, PHP_INT_MAX], 'loans' => collect(), 'total' => 0, 'count' => 0],
            ];

            foreach ($loans as $loan) {
                $daysOverdue = abs($asOfDate->diffInDays($loan->maturity_date));
                if ($daysOverdue <= 30) {
                    $bucketKey = '1-30';
                } elseif ($daysOverdue <= 60) {
                    $bucketKey = '31-60';
                } elseif ($daysOverdue <= 90) {
                    $bucketKey = '61-90';
                } elseif ($daysOverdue <= 120) {
                    $bucketKey = '91-120';
                } else {
                    $bucketKey = '121+';
                }

                $buckets[$bucketKey]['loans']->push($loan);
                $buckets[$bucketKey]['total'] += $loan->outstanding_balance;
                $buckets[$bucketKey]['count']++;
            }

            return $buckets;
        }

        public function getTotalOverdueBalanceProperty(): float
        {
            return array_sum(array_column($this->agingBuckets, 'total'));
        }

        public function getTotalOverdueCountProperty(): int
        {
            return array_sum(array_column($this->agingBuckets, 'count'));
        }

        public function getLoanTypeLabelProperty(): string
        {
            if (!$this->selectedLoanTypeId) {
                return 'All Loan Types';
            }

            return LoanType::find($this->selectedLoanTypeId)?->name ?? 'Loans';
        }

        public function render()
        {
            return view('livewire.bookkeeper.bookkeeper-loan-aging-tab');
        }
    }
