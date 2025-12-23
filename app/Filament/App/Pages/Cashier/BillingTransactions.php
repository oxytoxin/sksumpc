<?php

    namespace App\Filament\App\Pages\Cashier;

    use App\Enums\FromBillingTypes;
    use App\Models\CapitalSubscriptionBillingPayment;
    use App\Models\CashCollectibleBillingPayment;
    use App\Models\LoanBillingPayment;
    use App\Models\MsoBillingPayment;
    use Filament\Actions\Contracts\HasActions;
    use Filament\Actions\Concerns\InteractsWithActions;
    use Filament\Infolists\Components\TextEntry;
    use Filament\Schemas\Schema;
    use Filament\Schemas\Components\Actions;
    use Filament\Actions\Action;
    use App\Models\CapitalSubscriptionBilling;
    use App\Models\CashCollectibleBilling;
    use App\Models\LoanBilling;
    use App\Models\MsoBilling;
    use Filament\Forms\Components\Placeholder;
    use Filament\Forms\Components\Select;
    use Filament\Forms\Components\TextInput;
    use Filament\Forms\Concerns\InteractsWithForms;
    use Filament\Forms\Contracts\HasForms;
    use Filament\Notifications\Notification;
    use Filament\Support\Enums\Width;
    use Livewire\Component;

    class BillingTransactions extends Component implements HasForms, HasActions
    {
        use InteractsWithActions;
        use InteractsWithForms, RequiresBookkeeperTransactionDate;

        public $data = [];

        public function form(Schema $schema)
        {
            return $schema
                ->statePath('data')
                ->components([
                    Select::make('type')
                        ->options([
                            FromBillingTypes::CAPITAL_SUBSCRIPTION_BILLING->value => 'Capital Subscription',
                            FromBillingTypes::LOAN_BILLING->value => 'Loan',
                            FromBillingTypes::CASH_COLLECTIBLE_BILLING->value => 'Stakeholders',
                            FromBillingTypes::MSO_BILLING->value => 'MSO',
                        ])
                        ->reactive(),
                    Select::make('billing_id')
                        ->label('Billing')
                        ->reactive()
                        ->options(function ($get) {
                            switch ($get('type')) {
                                case FromBillingTypes::CAPITAL_SUBSCRIPTION_BILLING->value:
                                    return CapitalSubscriptionBilling::where('for_or', true)->pluck('reference_number', 'id');
                                    break;
                                case FromBillingTypes::LOAN_BILLING->value:
                                    return LoanBilling::where('for_or', true)->pluck('reference_number', 'id');
                                    break;
                                case FromBillingTypes::CASH_COLLECTIBLE_BILLING->value:
                                    return CashCollectibleBilling::where('for_or', true)->pluck('reference_number', 'id');
                                    break;
                                case FromBillingTypes::MSO_BILLING->value:
                                    return MsoBilling::where('for_or', true)->pluck('reference_number', 'id');
                                    break;
                                default:
                                    return [];
                                    break;
                            }
                        }),
                    TextEntry::make('loan_type')
                        ->visible(fn($get) => $get('type') == FromBillingTypes::LOAN_BILLING->value && $get('billing_id'))
                        ->state(fn($get) => LoanBilling::find($get('billing_id'))?->loan_type->name),
                    TextEntry::make('amount_due')
                        ->state(fn($get) => match ($get('type')) {
                            FromBillingTypes::CAPITAL_SUBSCRIPTION_BILLING->value => renumber_format(CapitalSubscriptionBillingPayment::where('capital_subscription_billing_id', $get('billing_id'))->sum('amount_due')),
                            FromBillingTypes::LOAN_BILLING->value => renumber_format(LoanBillingPayment::where('loan_billing_id', $get('billing_id'))->sum('amount_due')),
                            FromBillingTypes::CASH_COLLECTIBLE_BILLING->value => renumber_format(CashCollectibleBillingPayment::where('cash_collectible_billing_id', $get('billing_id'))->sum('amount_due')),
                            FromBillingTypes::MSO_BILLING->value => renumber_format(MsoBillingPayment::where('mso_billing_id', $get('billing_id'))->sum('amount_due')),
                            default => 0
                        })
                        ->visible(fn($get) => $get('billing_id')),
                    TextEntry::make('amount_paid')
                        ->state(fn($get) => match ($get('type')) {
                            FromBillingTypes::CAPITAL_SUBSCRIPTION_BILLING->value => renumber_format(CapitalSubscriptionBillingPayment::where('capital_subscription_billing_id', $get('billing_id'))->sum('amount_paid')),
                            FromBillingTypes::LOAN_BILLING->value => renumber_format(LoanBillingPayment::where('loan_billing_id', $get('billing_id'))->sum('amount_paid')),
                            FromBillingTypes::CASH_COLLECTIBLE_BILLING->value => renumber_format(CashCollectibleBillingPayment::where('cash_collectible_billing_id', $get('billing_id'))->sum('amount_paid')),
                            FromBillingTypes::MSO_BILLING->value => renumber_format(MsoBillingPayment::where('mso_billing_id', $get('billing_id'))->sum('amount_paid')),
                            default => 0
                        })
                        ->visible(fn($get) => $get('billing_id')),
                    TextInput::make('name')->required(),
                    TextInput::make('or_number')->required()->label('OR #'),
                    Actions::make([
                        Action::make('submit')
                            ->action(function () {
                                $data = $this->form->getState();
                                $record = match ((int) $data['type']) {
                                    FromBillingTypes::CAPITAL_SUBSCRIPTION_BILLING->value => CapitalSubscriptionBilling::find($data['billing_id']),
                                    FromBillingTypes::LOAN_BILLING->value => LoanBilling::find($data['billing_id']),
                                    FromBillingTypes::CASH_COLLECTIBLE_BILLING->value => CashCollectibleBilling::find($data['billing_id']),
                                    FromBillingTypes::MSO_BILLING->value => MsoBilling::find($data['billing_id']),
                                    default => null
                                };

                                $record->update([
                                    'name' => $data['name'],
                                    'or_number' => $data['or_number'],
                                    'or_date' => config('app.transaction_date') ?? today(),
                                    'for_or' => false,
                                ]);
                                Notification::make()->title('OR created for billing!')->success()->send();
                                $this->form->fill();

                                $this->replaceMountedAction('receipt', [
                                    'name' => $record->name,
                                    'or_number' => $record->or_number,
                                    'or_date' => $record->or_date,
                                    'reference_number' => $record->reference_number,
                                    'billable_date' => $record->billable_date,
                                    'date' => $record->date,
                                    'total_amount_due' => match ($data['type']) {
                                        FromBillingTypes::CAPITAL_SUBSCRIPTION_BILLING->value => renumber_format(CapitalSubscriptionBillingPayment::where('capital_subscription_billing_id', $data['billing_id'])->sum('amount_due')),
                                        FromBillingTypes::LOAN_BILLING->value => renumber_format(LoanBillingPayment::where('loan_billing_id', $data['billing_id'])->sum('amount_due')),
                                        FromBillingTypes::CASH_COLLECTIBLE_BILLING->value => renumber_format(CashCollectibleBillingPayment::where('cash_collectible_billing_id', $data['billing_id'])->sum('amount_due')),
                                        FromBillingTypes::MSO_BILLING->value => renumber_format(MsoBillingPayment::where('mso_billing_id', $data['billing_id'])->sum('amount_due')),
                                        default => 0
                                    },
                                    'total_amount_paid' => match ($data['type']) {
                                        FromBillingTypes::CAPITAL_SUBSCRIPTION_BILLING->value => renumber_format(CapitalSubscriptionBillingPayment::where('capital_subscription_billing_id', $data['billing_id'])->sum('amount_paid')),
                                        FromBillingTypes::LOAN_BILLING->value => renumber_format(LoanBillingPayment::where('loan_billing_id', $data['billing_id'])->sum('amount_paid')),
                                        FromBillingTypes::CASH_COLLECTIBLE_BILLING->value => renumber_format(CashCollectibleBillingPayment::where('cash_collectible_billing_id', $data['billing_id'])->sum('amount_paid')),
                                        FromBillingTypes::MSO_BILLING->value => renumber_format(MsoBillingPayment::where('mso_billing_id', $data['billing_id'])->sum('amount_paid')),
                                        default => 0
                                    }
                                ]);
                            }),
                        Action::make('billing_receivables')
                            ->url(
                                function ($get) {
                                    if ($get('billing_id')) {
                                        return match ((int) $get('type')) {
                                            FromBillingTypes::CAPITAL_SUBSCRIPTION_BILLING->value => route('filament.app.resources.capital-subscription-billings.billing-payments', ['capital_subscription_billing' => $get('billing_id')]),
                                            FromBillingTypes::LOAN_BILLING->value => route('filament.app.resources.loan-billings.billing-payments', ['loan_billing' => $get('billing_id')]),
                                            FromBillingTypes::CASH_COLLECTIBLE_BILLING->value => route('filament.app.resources.cash-collectible-billings.billing-payments', ['cash_collectible_billing' => $get('billing_id')]),
                                            FromBillingTypes::MSO_BILLING->value => route('filament.app.resources.mso-billings.billing-payments', ['mso_billing' => $get('billing_id')]),
                                            default => '#'
                                        };
                                    }
                                }
                            )
                            ->visible(fn($get) => $get('billing_id'))
                            ->openUrlInNewTab()
                            ->button()
                            ->outlined(),
                        Action::make('print')
                            ->url(fn($get) => route('filament.app.resources.loan-billings.statement-of-remittance', ['loan_billing' => $get('billing_id')]))
                            ->visible(fn($get) => $get('type') == FromBillingTypes::LOAN_BILLING->value && $get('billing_id'))
                            ->icon('heroicon-o-printer')
                            ->button()
                            ->openUrlInNewTab()
                            ->outlined(),
                    ]),
                ]);
        }

        public function receipt(): Action
        {
            return Action::make('receipt')
                ->modalContent(fn($arguments) => view('filament.app.pages.cashier.billing-or-receipt', [
                    'name' => $arguments['name'],
                    'or_number' => $arguments['or_number'],
                    'or_date' => $arguments['or_date'],
                    'reference_number' => $arguments['reference_number'],
                    'billable_date' => $arguments['billable_date'],
                    'date' => $arguments['date'],
                    'total_amount_due' => $arguments['total_amount_due'],
                    'total_amount_paid' => $arguments['total_amount_paid'],
                ]))
                ->modalCancelAction(false)
                ->closeModalByClickingAway(false)
                ->modalCloseButton(false)
                ->modalSubmitAction(function (Action $action) {
                    $action
                        ->label('Close')
                        ->extraAttributes(['class' => 'w-full !-mt-4'], true);
                })
                ->modalWidth(Width::FourExtraLarge)
                ->modalHeading('Billing OR Receipt')
                ->action(function () {
                    $this->js("setTimeout(() => document.querySelector('html').style = '', 10)");
                });
        }

        public function mount()
        {
            $this->form->fill();
        }

        public function render()
        {
            return view('filament.app.pages.cashier.billing-transactions');
        }
    }
