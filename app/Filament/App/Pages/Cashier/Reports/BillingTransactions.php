<?php

    namespace App\Filament\App\Pages\Cashier\Reports;

    use App\Models\CapitalSubscriptionBilling;
    use App\Models\MsoBilling;
    use App\Models\LoanBilling;
    use App\Models\CashCollectibleBilling;
    use DB;
    use Filament\Pages\Page;
    use Filament\Tables\Columns\TextColumn;
    use Filament\Tables\Concerns\InteractsWithTable;
    use Filament\Tables\Contracts\HasTable;
    use Filament\Tables\Enums\FiltersLayout;
    use Filament\Tables\Filters\SelectFilter;
    use Filament\Tables\Table;
    use Malzariey\FilamentDaterangepickerFilter\Filters\DateRangeFilter;

    class BillingTransactions extends Page implements HasTable
    {
        use HasSignatories, InteractsWithTable;

        protected static bool $shouldRegisterNavigation = false;

        protected string $view = 'filament.app.pages.cashier.reports.billing-transactions';

        protected static ?string $title = 'BILLING TRANSACTIONS';

        protected function getTableQuery()
        {
            // Get MSO Billings
            $msoQuery = MsoBilling::where('posted', true)
                ->selectRaw('reference_number, date, "MSO Billing" as billing_type, (SELECT COALESCE(SUM(amount_paid), 0) FROM mso_billing_payments WHERE mso_billing_id = mso_billings.id) as amount');
            // Get Capital Subscription Billings
            $cbuQuery = CapitalSubscriptionBilling::where('posted', true)
                ->selectRaw('reference_number, date, "Capital Subscription Billing" as billing_type, (SELECT COALESCE(SUM(amount_paid), 0) FROM capital_subscription_billing_payments WHERE capital_subscription_billing_id = capital_subscription_billings.id) as amount');

            // Get Loan Billings
            $loanQuery = \App\Models\LoanBilling::where('posted', true)
                ->selectRaw('reference_number, date, "Loan Billing" as billing_type, (SELECT COALESCE(SUM(amount_paid), 0) FROM loan_billing_payments WHERE loan_billing_id = loan_billings.id) as amount');

            // Get Cash Collectible Billings
            $cashQuery = \App\Models\CashCollectibleBilling::where('posted', true)
                ->selectRaw('reference_number, date, "Cash Collectible Billing" as billing_type, (SELECT COALESCE(SUM(amount_paid), 0) FROM cash_collectible_billing_payments WHERE cash_collectible_billing_id = cash_collectible_billings.id) as amount');

            // Union all queries
            return MsoBilling::query()->fromSub($msoQuery->unionAll($cbuQuery)->unionAll($loanQuery)->unionAll($cashQuery), 'union_all_query');
        }

        public function table(Table $table): Table
        {
            return $table
                ->query($this->getTableQuery())
                ->defaultSort('date', 'desc')
                ->defaultKeySort(false)
                ->content(function ($livewire) {
                    return view('filament.app.pages.cashier.reports.billing-transactions-report-table', [
                        'signatories' => $this->signatories,
                        'report_title' => 'BILLING PAYMENTS REPORT',
                    ]);
                })
                ->columns([
                    TextColumn::make('no')
                        ->label('NO.')
                        ->rowIndex(),
                    TextColumn::make('date')
                        ->label('DATE')
                        ->date('m/d/Y'),
                    TextColumn::make('reference_number')
                        ->label('REFERENCE NUMBER'),
                    TextColumn::make('billing_type')
                        ->label('BILLING TYPE'),
                    TextColumn::make('amount')
                        ->label('AMOUNT')
                        ->money('PHP'),
                    TextColumn::make('running_total')
                        ->label('RUNNING TOTAL')
                        ->money('PHP'),
                ])
                ->filters([
                    SelectFilter::make('billing_type')
                        ->options([
                            'MSO Billing' => 'MSO Billing',
                            'Capital Subscription Billing' => 'Capital Subscription Billing',
                            'Loan Billing' => 'Loan Billing',
                            'Cash Collectible Billing' => 'Cash Collectible Billing',
                        ])
                        ->modifyQueryUsing(function ($query, $data) {
                            $query->when($data['value'], function ($q, $v) {
                                $q->where('billing_type', $v);
                            });
                        })
                        ->label('Billing Type'),
                    DateRangeFilter::make('date')
                        ->format('m/d/Y')
                        ->displayFormat('MM/DD/YYYY'),
                ])
                ->emptyStateHeading('No billing transactions found.')
                ->filtersLayout(FiltersLayout::AboveContent)
                ->paginated(false);
        }

        public function mount()
        {
            data_set($this, 'tableFilters.date.date', (config('app.transaction_date')?->format('m/d/Y') ?? today()->format('m/d/Y')).' - '.(config('app.transaction_date')?->format('m/d/Y') ?? today()->format('m/d/Y')));
        }
    }
