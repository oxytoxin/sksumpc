<?php

namespace App\Filament\App\Pages\Cashier\Actions;

use App\Actions\CapitalSubscription\PayCapitalSubscription;
use App\Actions\Transactions\CreateTransaction;
use App\Enums\PaymentTypes;
use App\Models\Account;
use App\Models\Member;
use App\Models\PaymentType;
use App\Oxytoxin\DTO\Transactions\TransactionData;
use Filament\Notifications\Notification;
use Illuminate\Validation\ValidationException;

class CashierTransactionsPageCbuPayment
{
    public static function handle(TransactionData $data): array
    {
        $member = Member::find($data->member_id);
        if (! $member->active_capital_subscription) {
            Notification::make()
                ->title('Error')
                ->body('Member has no active capital subscription.')
                ->danger()
                ->send();
            throw ValidationException::withMessages(['member_id' => 'Member has no active capital subscription.']);
        }

        app(PayCapitalSubscription::class)->handle($member->active_capital_subscription, $data);

        $data->debit = $data->credit;
        $data->credit = null;
        $cash_in_bank_account_id = Account::getCashInBankGF()->id;
        $cash_on_hand_account_id = Account::getCashOnHand()->id;
        if ($data->payment_type_id == PaymentTypes::ADA->value) {
            $data->account_id = $cash_in_bank_account_id;
        } else {
            $data->account_id = $cash_on_hand_account_id;
        }
        app(CreateTransaction::class)->handle($data);

        return [
            'account_number' => $member->capital_subscription_account->number,
            'account_name' => $member->capital_subscription_account->name,
            'reference_number' => $data->reference_number,
            'amount' => $data->debit,
            'payment_type' => PaymentType::find($data->payment_type_id)?->name,
            'payee' => $data->payee,
            'remarks' => 'CBU PAYMENT',
        ];
    }
}
