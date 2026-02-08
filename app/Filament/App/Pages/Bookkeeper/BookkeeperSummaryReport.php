<?php

namespace App\Filament\App\Pages\Bookkeeper;

use App\Models\ImprestAccount;
use App\Models\Loan;
use App\Models\LoanPayment;
use App\Models\LoveGiftAccount;
use App\Models\SavingsAccount;
use Carbon\CarbonImmutable;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Forms\Contracts\HasForms;
use Filament\Pages\Page;
use Filament\Schemas\Schema;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Livewire\Attributes\Computed;

class BookkeeperSummaryReport extends Page implements HasForms
{
    use InteractsWithForms;

    protected static ?int $navigationSort = 3;

    protected static string|\UnitEnum|null $navigationGroup = 'Bookkeeping';

    protected static ?string $title = 'Bookkeeper Summary Report';

    protected string $view = 'filament.app.pages.bookkeeper.bookkeeper-summary-report';

    public $data = [];

    public static function shouldRegisterNavigation(): bool
    {
        return Auth::user()->can('manage bookkeeping');
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
    public function totalSavingsBalance(): float
    {
        $accountIds = SavingsAccount::query()->pluck('id');

        $balances = DB::table('transactions')
            ->selectRaw('account_id, SUM(credit) as total_credit, SUM(debit) as total_debit')
            ->whereIn('account_id', $accountIds)
            ->whereDate('transaction_date', '<=', $this->asOfDate)
            ->groupBy('account_id')
            ->get();

        return $balances->sum(fn ($balance) => $balance->total_credit - $balance->total_debit);
    }

    #[Computed]
    public function totalImprestBalance(): float
    {
        $accountIds = ImprestAccount::query()->pluck('id');

        $balances = DB::table('transactions')
            ->selectRaw('account_id, SUM(credit) as total_credit, SUM(debit) as total_debit')
            ->whereIn('account_id', $accountIds)
            ->whereDate('transaction_date', '<=', $this->asOfDate)
            ->groupBy('account_id')
            ->get();

        return $balances->sum(fn ($balance) => $balance->total_credit - $balance->total_debit);
    }

    #[Computed]
    public function totalLoveGiftBalance(): float
    {
        $accountIds = LoveGiftAccount::query()->pluck('id');

        $balances = DB::table('transactions')
            ->selectRaw('account_id, SUM(credit) as total_credit, SUM(debit) as total_debit')
            ->whereIn('account_id', $accountIds)
            ->whereDate('transaction_date', '<=', $this->asOfDate)
            ->groupBy('account_id')
            ->get();

        return $balances->sum(fn ($balance) => $balance->total_credit - $balance->total_debit);
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
    public function totalOverpaymentsAmount(): float
    {
        return $this->loanOverpaymentsExcess->sum('overpayment') + $this->loanOverpaymentsNegative->sum(fn ($loan) => abs($loan->outstanding_balance));
    }
}
