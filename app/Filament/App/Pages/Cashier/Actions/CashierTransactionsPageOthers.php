<?php

namespace App\Filament\App\Pages\Cashier\Actions;

use App\Actions\Transactions\CreateTransaction;
use App\Models\Account;
use App\Models\PaymentType;
use App\Oxytoxin\DTO\Transactions\TransactionData;

class CashierTransactionsPageOthers
{
    public static function handle(TransactionData $data)
    {
        $account = Account::find($data->account_id);
        $data->remarks = 'Cashier Transaction for Other Accounts';
        app(CreateTransaction::class)->handle($data);
        $data->debit = $data->credit;
        $data->credit = null;
        $data->account_id = Account::getCashOnHand()->id;
        app(CreateTransaction::class)->handle($data);

        return [
            'account_number' => $account->number,
            'account_name' => $account->name,
            'reference_number' => $data->reference_number,
            'amount' => $data->debit, //this is originally credit
            'payment_type' => PaymentType::find($data->payment_type_id)->name,
            'payee' => $data->payee,
            'remarks' => 'OTHER PAYMENTS',
        ];
    }
}
