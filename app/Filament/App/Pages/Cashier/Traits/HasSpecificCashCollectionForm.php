<?php

namespace App\Filament\App\Pages\Cashier\Traits;

use App\Actions\CapitalSubscription\PayCapitalSubscription;
use App\Actions\CashCollections\PayCashCollectible;
use App\Actions\Imprests\DepositToImprestAccount;
use App\Actions\Imprests\WithdrawFromImprestAccount;
use App\Actions\Loans\PayLoan;
use App\Actions\LoveGifts\DepositToLoveGiftsAccount;
use App\Actions\LoveGifts\WithdrawFromLoveGiftsAccount;
use App\Actions\Savings\DepositToSavingsAccount;
use App\Actions\Savings\WithdrawFromSavingsAccount;
use App\Actions\TimeDeposits\CreateTimeDeposit;
use App\Models\CashCollectible;
use App\Models\LoanAccount;
use App\Models\Member;
use App\Models\PaymentType;
use App\Models\SavingsAccount;
use App\Models\TransactionType;
use App\Oxytoxin\DTO\CapitalSubscription\CapitalSubscriptionPaymentData;
use App\Oxytoxin\DTO\CashCollectibles\CashCollectiblePaymentData;
use App\Oxytoxin\DTO\Loan\LoanPaymentData;
use App\Oxytoxin\DTO\MSO\ImprestData;
use App\Oxytoxin\DTO\MSO\LoveGiftData;
use App\Oxytoxin\DTO\MSO\SavingsData;
use App\Oxytoxin\DTO\MSO\TimeDepositData;
use App\Oxytoxin\Providers\ImprestsProvider;
use App\Oxytoxin\Providers\LoveGiftProvider;
use App\Oxytoxin\Providers\SavingsProvider;
use App\Oxytoxin\Providers\TimeDepositsProvider;
use Filament\Forms\Components\Actions;
use Filament\Forms\Components\Actions\Action;
use Filament\Forms\Components\Placeholder;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Form;
use Filament\Notifications\Notification;
use Illuminate\Support\Facades\DB;

trait HasSpecificCashCollectionForm
{
    public $data = [];
    private function generateForm(Form $form, $transaction_date, $cash_collectibles): Form
    {
        return $form
            ->statePath('data')
            ->schema([
                Placeholder::make('transaction_date')
                    ->content($transaction_date?->format('m/d/Y')),
                Select::make('member_id')
                    ->label('Member')
                    ->options(Member::pluck('full_name', 'id'))
                    ->searchable()
                    ->afterStateUpdated(function($set, $state){
                        $set('transactions', []);
                        $set('payee', Member::find($state)->full_name);
                    })
                    ->reactive(),
                Placeholder::make('member_type')
                    ->content(fn($get) => Member::find($get('member_id'))?->member_type->name),
                Select::make('cash_collectible_id')
                    ->options($cash_collectibles)
                    ->label('Cash Collectible')
                    ->required(),
                TextInput::make('payee')
                    ->required(),
                Select::make('payment_type_id')
                    ->paymenttype()
                    ->required(),
                TextInput::make('reference_number')->required()
                    ->unique('cash_collectible_payments'),
                TextInput::make('amount')->required()->moneymask(),
                Actions::make([
                    Action::make('submit')
                        ->action(function () {
                            DB::beginTransaction();
                            $formData = $this->form->getState();
                            $member = Member::find($formData['member_id']);
                            $payment_types = PaymentType::get();
                            $cashCollectible = CashCollectible::find($formData['cash_collectible_id']);
                            app(PayCashCollectible::class)->handle($cashCollectible, new CashCollectiblePaymentData(
                                member_id: $member?->id,
                                payee: $formData['payee'],
                                payment_type_id: $formData['payment_type_id'],
                                reference_number: $formData['reference_number'],
                                amount: $formData['amount'],
                                transaction_date: $this->transaction_date,
                            ), TransactionType::firstWhere('name', 'CRJ'));
                            $transactions[] = [
                                'account_number' => '',
                                'account_name' => '',
                                'reference_number' => $formData['reference_number'],
                                'amount' => $formData['amount'],
                                'payment_type' => $payment_types->firstWhere('id', $formData['payment_type_id'])?->name ?? 'CASH',
                                'remarks' => 'CASH COLLECTIBLE PAYMENT: ' . strtoupper($cashCollectible->name)
                            ];
                            DB::commit();
                            Notification::make()->title('Transactions successful!')->success()->send();
                            $this->form->fill();
                            $this->replaceMountedAction('receipt', ['transactions' => $transactions]);
                        }),
                    ]),
            ]);
    }
}