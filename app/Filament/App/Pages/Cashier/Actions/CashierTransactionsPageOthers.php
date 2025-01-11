<?php

namespace App\Filament\App\Pages\Cashier\Actions;

use App\Models\Account;
use App\Enums\PaymentTypes;
use App\Models\PaymentType;
use App\Actions\Transactions\CreateTransaction;
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
        $cash_in_bank_account_id = Account::getCashInBankGF()->id;
        $cash_on_hand_account_id = Account::getCashOnHand()->id;
        if ($data->payment_type_id == PaymentTypes::ADA->value) {
            $data->account_id = $cash_in_bank_account_id;
        } else {
            $data->account_id = $cash_on_hand_account_id;
        }
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
