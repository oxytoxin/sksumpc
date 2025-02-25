<?php

namespace App\Filament\App\Pages\Cashier;

use App\Models\CapitalSubscriptionBilling;
use App\Models\CashCollectibleBilling;
use App\Models\LoanBilling;
use App\Models\MsoBilling;
use Filament\Forms\Components\Actions;
use Filament\Forms\Components\Actions\Action;
use Filament\Forms\Components\Placeholder;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Forms\Contracts\HasForms;
use Filament\Forms\Form;
use Filament\Notifications\Notification;
use Livewire\Component;

class BillingTransactions extends Component implements HasForms
{
    use InteractsWithForms, RequiresBookkeeperTransactionDate;

    public $data = [];

    public function form(Form $form)
    {
        return $form
            ->statePath('data')
            ->schema([
                Select::make('type')
                    ->options([
                        1 => 'Capital Subscription',
                        2 => 'Loan',
                        3 => 'Stakeholders',
                        4 => 'MSO',
                    ])
                    ->reactive(),
                Select::make('billing_id')
                    ->label('Billing')
                    ->reactive()
                    ->options(function ($get) {
                        switch ($get('type')) {
                            case 1:
                                return CapitalSubscriptionBilling::where('for_or', true)->pluck('reference_number', 'id');
                                break;
                            case 2:
                                return LoanBilling::where('for_or', true)->pluck('reference_number', 'id');
                                break;
                            case 3:
                                return CashCollectibleBilling::where('for_or', true)->pluck('reference_number', 'id');
                                break;
                            case 4:
                                return MsoBilling::where('for_or', true)->pluck('reference_number', 'id');
                                break;
                            default:
                                return [];
                                break;
                        }
                    }),
                Placeholder::make('loan_type')
                    ->visible(fn($get) => $get('type') == 2 && $get('billing_id'))
                    ->content(fn($get) => LoanBilling::find($get('billing_id'))?->loan_type->name),
                TextInput::make('name')->required(),
                TextInput::make('or_number')->required()->label('OR #'),
                Actions::make([
                    Action::make('submit')
                        ->action(function () {
                            $data = $this->form->getState();
                            $record = match ((int) $data['type']) {
                                1 => CapitalSubscriptionBilling::find($data['billing_id']),
                                2 => LoanBilling::find($data['billing_id']),
                                3 => CashCollectibleBilling::find($data['billing_id']),
                                4 => MsoBilling::find($data['billing_id']),
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
                        }),
                    Action::make('billing_receivables')
                        ->url(
                            function ($get) {
                                if ($get('billing_id')) {
                                    return match ((int) $get('type')) {
                                        1 => route('filament.app.resources.capital-subscription-billings.billing-payments', ['capital_subscription_billing' => $get('billing_id')]),
                                        2 => route('filament.app.resources.loan-billings.billing-payments', ['loan_billing' => $get('billing_id')]),
                                        3 => route('filament.app.resources.cash-collectible-billings.billing-payments', ['cash_collectible_billing' => $get('billing_id')]),
                                        4 => route('filament.app.resources.mso-billings.billing-payments', ['mso_billing' => $get('billing_id')]),
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
                        ->visible(fn($get) => $get('type') == 2 && $get('billing_id'))
                        ->icon('heroicon-o-printer')
                        ->button()
                        ->openUrlInNewTab()
                        ->outlined(),
                ]),
            ]);
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
