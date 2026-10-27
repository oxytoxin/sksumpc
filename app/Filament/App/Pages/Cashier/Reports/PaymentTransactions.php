<?php

namespace App\Filament\App\Pages\Cashier\Reports;

use App\Enums\OthersTransactionExcludedAccounts;
use App\Enums\PaymentTypes;
use App\Enums\TransactionTypes;
use App\Models\Account;
use App\Models\CapitalSubscriptionBilling;
use App\Models\CashCollectibleBilling;
use App\Models\LoanBilling;
use App\Models\LoanPayment;
use App\Models\LoanType;
use App\Models\MemberCreditAndBackground;
use App\Models\MsoBilling;
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
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;
use Malzariey\FilamentDaterangepickerFilter\Filters\DateRangeFilter;

class PaymentTransactions extends Page implements HasTable
{
    use HasSignatories, InteractsWithTable;

    protected static bool $shouldRegisterNavigation = false;

    protected static ?string $title = 'OR TRANSACTIONS';

    protected string $view = 'filament.app.pages.cashier.reports.payment-transactions';

    public $report_title = 'OR TRANSACTIONS';

    public function table(Table $table): Table
    {
        return $table
            ->defaultSort('transaction_date')
            ->defaultKeySort(false)
            ->query(function ($livewire) {
                $type = $livewire->tableFilters['transaction_type']['transaction_type'];
                $loan_type_id = $livewire->tableFilters['transaction_type']['loan_type_id'];
                if ($type == 'loan') {
                    return $this->getLoanReportQuery($loan_type_id);
                }
                if ($type == 'rice') {
                    return $this->getOrReportQuery($type)->whereIn('account_id', [OthersTransactionExcludedAccounts::RICE->value]);
                }
                if ($type == 'dormitory') {
                    return $this->getOrReportQuery($type)->whereIn('account_id', [OthersTransactionExcludedAccounts::RESERVATION_FEES_DORM->value, OthersTransactionExcludedAccounts::DORMITORY, OthersTransactionExcludedAccounts::RESERVATION->value]);
                }
                if ($type == 'laboratory') {
                    return $this->getOrReportQuery($type)
                        ->where(function ($query) {
                            $query->whereIn('account_id', [OthersTransactionExcludedAccounts::MEMBERSHIP_FEES->value])
                                ->orWhere(fn ($query) => $query->whereRelation('account', function ($query) {
                                    return $query->whereRelation('parent', 'tag', 'member_laboratory_cbu_paid');
                                }));
                        });
                }

                if (blank($type) || $type === 'billing') {
                    return $this->getOrReportQuery($type);
                }

                return $this->getOrReportQuery($type)->whereDoesntHave('account', function ($query) {
                    return $query->whereHas(
                        'ancestorsAndSelf',
                        fn ($q) => $q->whereIn('id', OthersTransactionExcludedAccounts::ids())
                    );
                });
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
                SelectFilter::make('billing_type')
                    ->label('Billing Type')
                    ->options(collect($this->billingReportSources())->pluck('label', 'label')->all())
                    ->visible(fn (): bool => blank($this->tableFilters['transaction_type']['transaction_type'] ?? null) || ($this->tableFilters['transaction_type']['transaction_type'] ?? null) === 'billing')
                    ->query(function (Builder $query, array $state): Builder {
                        $type = $this->tableFilters['transaction_type']['transaction_type'] ?? null;

                        return $query->when(
                            (blank($type) || $type === 'billing') && filled($state['value'] ?? null),
                            fn (Builder $query): Builder => $query->where('billing_type', $state['value']),
                        );
                    }),
                SelectFilter::make('member_type')
                    ->relationship('member.member_type', 'name')
                    ->query(fn (Builder $query, array $state): Builder => $this->filterReportMembers($query, $state['value'] ?? null, 'member_type_id')),
                SelectFilter::make('member_subtype')
                    ->relationship('member.member_subtype', 'name')
                    ->query(fn (Builder $query, array $state): Builder => $this->filterReportMembers($query, $state['value'] ?? null, 'member_subtype_id')),
                SelectFilter::make('division')
                    ->relationship('member.division', 'name')
                    ->query(fn (Builder $query, array $state): Builder => $this->filterReportMembers($query, $state['value'] ?? null, 'division_id')),
                SelectFilter::make('patronage_status')
                    ->relationship('member.patronage_status', 'name')
                    ->query(fn (Builder $query, array $state): Builder => $this->filterReportMembers($query, $state['value'] ?? null, 'patronage_status_id')),
                SelectFilter::make('gender')
                    ->relationship('member.gender', 'name')
                    ->query(fn (Builder $query, array $state): Builder => $this->filterReportMembers($query, $state['value'] ?? null, 'gender_id')),
                SelectFilter::make('status')
                    ->options([
                        1 => 'Active',
                        2 => 'Terminated',
                    ])
                    ->default(1)
                    ->query(
                        fn (Builder $query, array $state): Builder => $this->filterReportMembers($query, $state['value'] ?? null, 'terminated_at')
                    ),
                SelectFilter::make('civil_status')
                    ->relationship('member.credit_and_background.civil_status', 'name')
                    ->query(fn (Builder $query, array $state): Builder => $this->filterReportMembers($query, $state['value'] ?? null, 'credit_and_background.civil_status_id')),
                SelectFilter::make('occupation')
                    ->relationship('member.credit_and_background.occupation', 'name')
                    ->query(fn (Builder $query, array $state): Builder => $this->filterReportMembers($query, $state['value'] ?? null, 'credit_and_background.occupation_id')),
                SelectFilter::make('highest_educational_attainment')
                    ->label('Highest Educational Attainment')
                    ->options(MemberCreditAndBackground::whereNotNull('highest_educational_attainment')
                        ->distinct('highest_educational_attainment')
                        ->pluck('highest_educational_attainment', 'highest_educational_attainment'))
                    ->searchable()
                    ->preload()
                    ->query(
                        fn (Builder $query, array $state): Builder => $this->filterReportMembers($query, $state['value'] ?? null, 'credit_and_background.highest_educational_attainment')
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
                                'billing' => 'BILLINGS',
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

    /**
     * @return array<string, array{model: class-string<Model>, payments: string, label: string}>
     */
    protected function billingReportSources(): array
    {
        return [
            'mso' => ['model' => MsoBilling::class, 'payments' => 'payments', 'label' => 'MSO Billing'],
            'cbu' => ['model' => CapitalSubscriptionBilling::class, 'payments' => 'capital_subscription_billing_payments', 'label' => 'Capital Subscription Billing'],
            'loan' => ['model' => LoanBilling::class, 'payments' => 'loan_billing_payments', 'label' => 'Loan Billing'],
            'cash' => ['model' => CashCollectibleBilling::class, 'payments' => 'cash_collectible_billing_payments', 'label' => 'Cash Collectible Billing'],
        ];
    }

    protected function getOrReportQuery(?string $type): Builder
    {
        $transactions = Transaction::query()
            ->whereNull('from_billing_type')
            ->where('transaction_type_id', TransactionTypes::CRJ->value)
            ->select(['id', 'member_id', 'account_id', 'payee', 'transaction_date', 'reference_number', 'credit', 'debit'])
            ->selectRaw("CONCAT('transaction-', id) as row_key, NULL as billing_type, NULL as billing_reference");

        if (blank($type) || $type === 'billing') {
            $transactions->whereDoesntHave('account.ancestorsAndSelf', fn (Builder $query): Builder => $query->whereIn('id', OthersTransactionExcludedAccounts::ids()));
        }

        if ($type === 'billing') {
            $transactions->whereRaw('1 = 0');
        }

        foreach ($this->billingReportSources() as $source => $definition) {
            if (filled($type) && $type !== 'billing' && $source !== 'cash' && ! ($type === 'others' && $source === 'cbu')) {
                continue;
            }

            $accountExpression = $source === 'cash' ? 'account_id' : 'NULL';
            $billings = $definition['model']::query()
                ->where('posted', true)
                ->selectRaw("id, NULL as member_id, {$accountExpression} as account_id, name as payee, COALESCE(or_date, date) as transaction_date, COALESCE(or_number, reference_number) as reference_number")
                ->withSum($definition['payments'].' as credit', 'amount_paid')
                ->selectRaw('0 as debit, CONCAT(?, id) as row_key, ? as billing_type, reference_number as billing_reference', [$source.'-', $definition['label']]);

            $transactions->unionAll($billings);
        }

        return Transaction::query()
            ->fromSub($transactions, 'transactions')
            ->with(['member', 'account']);
    }

    /**
     * @param  Model|array<string, mixed>  $record
     */
    public function getTableRecordKey(Model|array $record): string
    {
        return $record['row_key'];
    }

    protected function filterReportMembers(Builder $query, mixed $value, string $attribute): Builder
    {
        if (blank($value)) {
            return $query;
        }

        $constraint = function (Builder $members) use ($value, $attribute): Builder {
            if ($attribute === 'terminated_at') {
                return (int) $value === 1 ? $members->whereNull('terminated_at') : $members->whereNotNull('terminated_at');
            }

            if (str_contains($attribute, '.')) {
                [$relationship, $column] = explode('.', $attribute, 2);

                return $members->whereRelation($relationship, $column, $value);
            }

            return $members->where($attribute, $value);
        };

        if (($this->tableFilters['transaction_type']['transaction_type'] ?? null) === 'loan') {
            return $query->whereHas('member', $constraint);
        }

        return $query->where(function (Builder $rows) use ($constraint): void {
            $rows->whereHas('member', $constraint);

            foreach ($this->billingReportSources() as $definition) {
                $rows->orWhere(function (Builder $billings) use ($definition, $constraint): void {
                    $billings->where('billing_type', $definition['label'])
                        ->whereIn('id', $definition['model']::query()
                            ->select('id')
                            ->whereHas($definition['payments'].'.member', $constraint));
                });
            }
        });
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
