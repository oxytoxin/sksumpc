<?php

namespace App\Filament\App\Pages\Cashier\Actions;

use App\Actions\CapitalSubscription\PayCapitalSubscription;
use App\Actions\Transactions\CreateTransaction;
use App\Models\Account;
use App\Models\Member;
use App\Models\PaymentType;
use App\Oxytoxin\DTO\Transactions\TransactionData;

class CashierTransactionsPageCbuPayment
{
    public static function handle(TransactionData $data): array
    {
        $member = Member::find($data->member_id);
        app(PayCapitalSubscription::class)->handle($member->active_capital_subscription, $data);
        $data->debit = $data->credit;
        $data->credit = null;
        $data->account_id = Account::getCashOnHand()->id;
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
