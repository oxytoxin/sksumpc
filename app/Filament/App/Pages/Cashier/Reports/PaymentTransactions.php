<?php

namespace App\Filament\App\Pages\Cashier\Reports;

use App\Enums\OthersTransactionExcludedAccounts;
use App\Enums\PaymentTypes;
use App\Enums\TransactionTypes;
use App\Models\Account;
use App\Models\LoanPayment;
use App\Models\LoanType;
use App\Models\MemberCreditAndBackground;
use App\Models\Transaction;
use Filament\Forms\Components\Select;
use Filament\Pages\Page;
use Filament\Tables\Concerns\InteractsWithTable;
use Filament\Tables\Contracts\HasTable;
use Filament\Tables\Enums\FiltersLayout;
use Filament\Tables\Filters\Filter;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;
use Malzariey\FilamentDaterangepickerFilter\Filters\DateRangeFilter;

class PaymentTransactions extends Page implements HasTable
{
    use HasSignatories, InteractsWithTable;

    protected static bool $shouldRegisterNavigation = false;

    protected static ?string $title = 'PAYMENT TRANSACTIONS';

    protected string $view = 'filament.app.pages.cashier.reports.payment-transactions';

    public $report_title = 'PAYMENT TRANSACTIONS';

    public function table(Table $table): Table
    {
        return $table
            ->defaultKeySort(false)
            ->query(function ($livewire) {
                $type = $livewire->tableFilters['transaction_type']['transaction_type'];
                $loan_type_id = $livewire->tableFilters['transaction_type']['loan_type_id'];
                if ($type == 'loan') {
                    return $this->getLoanReportQuery($loan_type_id);
                }
                if ($type == 'rice') {
                    return Transaction::query()->whereIn('account_id', [OthersTransactionExcludedAccounts::RICE->value])->where('transaction_type_id', TransactionTypes::CRJ->value);
                }
                if ($type == 'dormitory') {
                    return Transaction::query()->whereIn('account_id', [OthersTransactionExcludedAccounts::RESERVATION_FEES_DORM->value, OthersTransactionExcludedAccounts::DORMITORY, OthersTransactionExcludedAccounts::RESERVATION->value])->where('transaction_type_id', TransactionTypes::CRJ->value);
                }
                if ($type == 'laboratory') {
                    return Transaction::query()
                        ->where(function ($query) {
                            $query->whereIn('account_id', [OthersTransactionExcludedAccounts::MEMBERSHIP_FEES->value])
                                ->orWhere(fn ($query) => $query->whereRelation('account', function ($query) {
                                    return $query->whereRelation('parent', 'tag', 'member_laboratory_cbu_paid');
                                }));
                        })
                        ->where('transaction_type_id', TransactionTypes::CRJ->value);
                }

                return Transaction::whereDoesntHave('account', function ($query) {
                    return $query->whereHas(
                        'ancestorsAndSelf',
                        fn ($q) => $q->whereIn('id', OthersTransactionExcludedAccounts::ids())
                    );
                })
                    ->where('transaction_type_id', TransactionTypes::CRJ->value);
            })
            ->content(function ($livewire) {
                $type = $livewire->tableFilters['transaction_type']['transaction_type'];
                if ($type == 'loan') {
                    return view('filament.app.pages.cashier.reports.loan-payments-report-table', [
                        'signatories' => $this->signatories,
                        'report_title' => "REPORT ON MEMBERS' LOAN PAYMENTS",
                    ]);
                }

                return view('filament.app.pages.cashier.reports.payment-transactions-report-table', [
                    'signatories' => $this->signatories,
                    'report_title' => $this->report_title,
                ]);
            })
            ->filters([
                SelectFilter::make('member_type')
                    ->relationship('member.member_type', 'name'),
                SelectFilter::make('member_subtype')
                    ->relationship('member.member_subtype', 'name'),
                SelectFilter::make('division')
                    ->relationship('member.division', 'name'),
                SelectFilter::make('patronage_status')
                    ->relationship('member.patronage_status', 'name'),
                SelectFilter::make('gender')
                    ->relationship('member.gender', 'name'),
                SelectFilter::make('status')
                    ->options([
                        1 => 'Active',
                        2 => 'Terminated',
                    ])
                    ->default(1)
                    ->query(
                        fn ($query, $state) => $query
                            ->when($state['value'] == 1, fn ($q) => $q->whereRelation('member', 'terminated_at', null))
                            ->when($state['value'] == 2, fn ($q) => $q->whereRelation('member', 'terminated_at', '!=', null))
                    ),
                SelectFilter::make('civil_status')
                    ->relationship('member.credit_and_background.civil_status', 'name'),
                SelectFilter::make('occupation')
                    ->relationship('member.credit_and_background.occupation', 'name'),
                SelectFilter::make('highest_educational_attainment')
                    ->label('Highest Educational Attainment')
                    ->options(MemberCreditAndBackground::whereNotNull('highest_educational_attainment')
                        ->distinct('highest_educational_attainment')
                        ->pluck('highest_educational_attainment', 'highest_educational_attainment'))
                    ->searchable()
                    ->preload()
                    ->query(
                        fn ($query, $state) => $query
                            ->when($state['value'], fn ($q, $v) => $q->whereRelation('member.credit_and_background', 'highest_educational_attainment', $v))
                    ),
                DateRangeFilter::make('transaction_date')
                    ->format('m/d/Y')
                    ->displayFormat('MM/DD/YYYY')
                    ->modifyQueryUsing(fn (Builder $query, ?Carbon $startDate, ?Carbon $endDate) => $this->applyLoanReportDateRange(
                        $query,
                        $startDate,
                        $endDate,
                    )),
                Filter::make('transaction_type')
                    ->columns(2)
                    ->columnSpan(2)
                    ->schema([
                        Select::make('transaction_type')
                            ->label('Transaction Type')
                            ->options([
                                'loan' => 'LOAN',
                                'others' => 'OTHERS',
                                'rice' => 'RICE',
                                'dormitory' => 'DORMITORY',
                                'laboratory' => 'LABORATORY',
                            ])
                            ->afterStateUpdated(function ($set) {
                                $set('account_id', null);
                                $set('loan_type_id', null);
                            }),
                        Select::make('account_id')
                            ->visible(fn ($get) => $get('transaction_type') == 'others')
                            ->options(Account::withCode()->whereDoesntHave('children', fn ($q) => $q->whereNull('member_id'))->where('member_id', null)->pluck('code', 'id'))
                            ->searchable()
                            ->label('Account'),
                        Select::make('loan_type_id')
                            ->visible(fn ($get) => $get('transaction_type') == 'loan')
                            ->options(LoanType::pluck('name', 'id'))
                            ->label('Loan Type'),
                    ])
                    ->query(fn ($query, $state) => $query->when($state['account_id'], fn ($query, $value) => $query->where('account_id', $value))),
            ])
            ->filtersLayout(FiltersLayout::AboveContent)
            ->paginated(false);
    }

    public function getLoanReportQuery(?int $loanTypeId = null): Builder
    {
        $query = LoanPayment::query()
            ->join('members', 'loan_payments.member_id', '=', 'members.id')
            ->join('loans', 'loan_payments.loan_id', '=', 'loans.id')
            ->join('accounts as loan_accounts', 'loans.loan_account_id', '=', 'loan_accounts.id')
            ->join('loan_types as payment_loan_types', 'loans.loan_type_id', '=', 'payment_loan_types.id')
            ->leftJoin('loan_billings', 'loan_payments.loan_billing_id', '=', 'loan_billings.id')
            ->leftJoin('loan_types as billing_loan_types', 'loan_billings.loan_type_id', '=', 'billing_loan_types.id')
            ->whereIn('loan_payments.payment_type_id', [
                PaymentTypes::CASH->value,
                // PaymentTypes::CHECK->value,
                PaymentTypes::ADA->value,
                PaymentTypes::DEPOSIT_SLIP->value,
            ])
            ->selectRaw("
                MIN(loan_payments.id) as id,
                CASE
                    WHEN loan_payments.loan_billing_id IS NULL THEN CONCAT('payment-', loan_payments.id)
                    ELSE CONCAT('billing-', loan_payments.loan_billing_id)
                END as row_key,
                CASE
                    WHEN loan_payments.loan_billing_id IS NULL THEN loan_payments.member_id
                    ELSE NULL
                END as member_id,
                CASE
                    WHEN loan_payments.loan_billing_id IS NULL THEN members.full_name
                    ELSE loan_billings.reference_number
                END as member_name,
                CASE
                    WHEN loan_payments.loan_billing_id IS NULL THEN loan_accounts.number
                    ELSE loan_billings.reference_number
                END as account_number,
                CASE
                    WHEN loan_payments.loan_billing_id IS NULL THEN payment_loan_types.name
                    ELSE billing_loan_types.name
                END as loan_type_name,
                CASE
                    WHEN loan_payments.loan_billing_id IS NULL THEN loan_payments.reference_number
                    ELSE loan_billings.reference_number
                END as reference_number,
                SUM(loan_payments.amount) as amount,
                SUM(loan_payments.principal_payment) as principal_payment,
                SUM(loan_payments.interest_payment) as interest_payment,
                SUM(loan_payments.surcharge_payment) as surcharge_payment,
                {$this->getLoanReportTransactionDateExpression()} as transaction_date
            ")
            ->groupByRaw("
                CASE
                    WHEN loan_payments.loan_billing_id IS NULL THEN CONCAT('payment-', loan_payments.id)
                    ELSE CONCAT('billing-', loan_payments.loan_billing_id)
                END,
                CASE
                    WHEN loan_payments.loan_billing_id IS NULL THEN loan_payments.member_id
                    ELSE NULL
                END,
                CASE
                    WHEN loan_payments.loan_billing_id IS NULL THEN members.full_name
                    ELSE loan_billings.reference_number
                END,
                CASE
                    WHEN loan_payments.loan_billing_id IS NULL THEN loan_accounts.number
                    ELSE loan_billings.reference_number
                END,
                CASE
                    WHEN loan_payments.loan_billing_id IS NULL THEN payment_loan_types.name
                    ELSE billing_loan_types.name
                END,
                CASE
                    WHEN loan_payments.loan_billing_id IS NULL THEN loan_payments.reference_number
                    ELSE loan_billings.reference_number
                END,
                {$this->getLoanReportTransactionDateExpression()}
            ")
            ->orderBy('transaction_date')
            ->orderBy('id');

        if ($loanTypeId) {
            $query->whereRelation('loan', 'loan_type_id', $loanTypeId);
        }

        return $query;
    }

    public function applyLoanReportDateRange(Builder $query, ?\DateTimeInterface $startDate, ?\DateTimeInterface $endDate): Builder
    {
        if ($startDate === null || $endDate === null) {
            return $query;
        }

        $from = $query->getQuery()->from;

        if (is_string($from) && str_contains($from, 'loan_payments')) {
            return $query->whereBetween(DB::raw($this->getLoanReportTransactionDateExpression()), [$startDate, $endDate]);
        }

        return $query->whereBetween('transaction_date', [$startDate, $endDate]);
    }

    public function getLoanReportTransactionDateExpression(): string
    {
        return 'CASE
                    WHEN loan_payments.loan_billing_id IS NULL THEN loan_payments.transaction_date
                    ELSE COALESCE(loan_billings.or_date, loan_billings.date)
                END';
    }

    public function mount()
    {
        data_set($this, 'tableFilters.transaction_date.transaction_date', (config('app.transaction_date')?->format('m/d/Y') ?? today()->format('m/d/Y')).' - '.(config('app.transaction_date')?->format('m/d/Y') ?? today()->format('m/d/Y')));
        data_set($this, 'tableFilters.transaction_type', ['transaction_type' => null, 'account_id' => null, 'loan_type_id' => null]);
    }
}
