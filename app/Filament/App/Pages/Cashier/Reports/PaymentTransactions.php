<?php

namespace App\Filament\App\Pages\Cashier\Reports;

use App\Models\Account;
use App\Models\LoanType;
use Filament\Pages\Page;
use App\Models\MemberType;
use Filament\Tables\Table;
use App\Enums\PaymentTypes;
use App\Models\LoanPayment;
use App\Models\Transaction;
use Filament\Tables\Filters\Filter;
use Filament\Forms\Components\Select;
use Filament\Tables\Contracts\HasTable;
use Filament\Tables\Enums\FiltersLayout;
use Filament\Tables\Filters\SelectFilter;
use App\Enums\OthersTransactionExcludedAccounts;
use App\Enums\TransactionTypes;
use Filament\Tables\Concerns\InteractsWithTable;
use Malzariey\FilamentDaterangepickerFilter\Filters\DateRangeFilter;

class PaymentTransactions extends Page implements HasTable
{
    use HasSignatories, InteractsWithTable;

    protected static bool $shouldRegisterNavigation = false;

    protected static ?string $title = 'PAYMENT TRANSACTIONS';

    protected static string $view = 'filament.app.pages.cashier.reports.payment-transactions';

    public $report_title = 'PAYMENT TRANSACTIONS';

    public function table(Table $table): Table
    {
        return $table
            ->query(function ($livewire) {
                $type = $livewire->tableFilters['transaction_type']['transaction_type'];
                $loan_type_id = $livewire->tableFilters['transaction_type']['loan_type_id'];
                if ($type == 'loan') {
                    $loan_query = LoanPayment::query()->with(['loan.loan_account'])->whereIn('payment_type_id', [
                        PaymentTypes::CASH->value,
                        PaymentTypes::CHECK->value,
                        PaymentTypes::ADA->value,
                        PaymentTypes::DEPOSIT_SLIP->value,
                    ]);
                    if ($loan_type_id) {
                        $loan_query->whereRelation('loan', 'loan_type_id', $loan_type_id);
                    }

                    return $loan_query;
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
                                ->orWhere(fn($query) => $query->whereRelation('account', function ($query) {
                                    return $query->whereRelation('parent', 'tag', 'member_laboratory_cbu_paid');
                                }));
                        })
                        ->where('transaction_type_id', TransactionTypes::CRJ->value);
                }

                return Transaction::whereDoesntHave('account', function ($query) {
                    return $query->whereHas(
                        'rootAncestor',
                        fn($q) => $q->whereIn('id', OthersTransactionExcludedAccounts::get())
                    );
                })
                    ->withoutMso()
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
                DateRangeFilter::make('transaction_date')
                    ->format('m/d/Y')
                    ->displayFormat('MM/DD/YYYY'),
                SelectFilter::make('member_type')
                    ->label('Member Type')
                    ->options(MemberType::pluck('name', 'id'))
                    ->query(fn($query, $state) => $query->when($state['value'], fn($q, $v) => $q->whereRelation('member', 'member_type_id', $state['value']))),
                Filter::make('transaction_type')
                    ->columns(2)
                    ->columnSpan(2)
                    ->form([
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
                            ->visible(fn($get) => $get('transaction_type') == 'others')
                            ->options(Account::withCode()->whereDoesntHave('children', fn($q) => $q->whereNull('member_id'))->where('member_id', null)->pluck('code', 'id'))
                            ->searchable()
                            ->label('Account'),
                        Select::make('loan_type_id')
                            ->visible(fn($get) => $get('transaction_type') == 'loan')
                            ->options(LoanType::pluck('name', 'id'))
                            ->label('Loan Type'),
                    ])
                    ->query(fn($query, $state) => $query->when($state['account_id'], fn($query, $value) => $query->where('account_id', $value))),
            ])
            ->filtersLayout(FiltersLayout::AboveContent)
            ->paginated(false);
    }

    public function mount()
    {
        data_set($this, 'tableFilters.transaction_date.transaction_date', (config('app.transaction_date')?->format('m/d/Y') ?? today()->format('m/d/Y')) . ' - ' . (config('app.transaction_date')?->format('m/d/Y') ?? today()->format('m/d/Y')));
        data_set($this, 'tableFilters.transaction_type', ['transaction_type' => null, 'account_id' => null, 'loan_type_id' => null]);
    }
}
