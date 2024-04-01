<?php

namespace App\Filament\App\Pages\Cashier;

use App\Models\CapitalSubscriptionBilling;
use App\Models\LoanBilling;
use Filament\Forms\Components\Actions;
use Filament\Forms\Components\Actions\Action;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Forms\Contracts\HasForms;
use Filament\Forms\Form;
use Filament\Notifications\Notification;
use Livewire\Component;

class BillingTransactions extends Component implements HasForms
{
    use InteractsWithForms;

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
                    ])
                    ->reactive(),
                Select::make('billing_id')
                    ->label('Billing')
                    ->options(function ($get) {
                        switch ($get('type')) {
                            case 1:
                                return CapitalSubscriptionBilling::where('for_or', true)->pluck('reference_number', 'id');
                                break;
                            case 2:
                                return LoanBilling::where('for_or', true)->pluck('reference_number', 'id');
                                break;
                            default:
                                return [];
                                break;
                        }
                    }),
                TextInput::make('name')->required(),
                TextInput::make('or_number')->required()->label('OR #'),
                Actions::make([
                    Action::make('submit')
                        ->action(function () {
                            $data = $this->form->getState();
                            if ($data['type'] == 1) {
                                $record = CapitalSubscriptionBilling::find($data['billing_id']);
                            }
                            if ($data['type'] == 2) {
                                $record = LoanBilling::find($data['billing_id']);
                            }
                            $record->update([
                                'name' => $data['name'],
                                'or_number' => $data['or_number'],
                                'for_or' => false,
                            ]);
                            Notification::make()->title('OR created for billing!')->success()->send();
                            $this->form->fill();
                        }),

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
