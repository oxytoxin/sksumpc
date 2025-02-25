<?php

namespace App\Actions\MsoBilling;

use App\Actions\MSO\DepositToMsoAccount;
use App\Actions\Transactions\CreateTransaction;
use App\Enums\MsoType;
use App\Enums\PaymentTypes;
use App\Models\Account;
use App\Models\MsoBilling;
use App\Models\MsoBillingPayment;
use App\Models\TransactionType;
use App\Oxytoxin\DTO\Transactions\TransactionData;
use Filament\Notifications\Notification;
use Illuminate\Support\Facades\DB;

class PostMsoBillingPayments
{
    public function handle(MsoBilling $msoBilling)
    {
        if (! $msoBilling->reference_number || ! $msoBilling->payment_type_id) {
            return Notification::make()->title('Billing reference number and payment type is missing!')->danger()->send();
        }
        DB::beginTransaction();
        $transaction_type = TransactionType::CRJ();
        $cash_in_bank_account_id = Account::getCashInBankGF()->id;
        $cash_on_hand_account_id = Account::getCashOnHand()->id;
        $msoBilling->payments()->with('member')->each(function (MsoBillingPayment $payment) use ($msoBilling, $transaction_type, $cash_in_bank_account_id, $cash_on_hand_account_id) {
            $member = $payment->member;
            $data = new TransactionData(
                account_id: $payment->account_id,
                transactionType: $transaction_type,
                reference_number: $msoBilling->or_number,
                payment_type_id: $msoBilling->payment_type_id,
                credit: $payment->amount_paid,
                member_id: $member->id,
                transaction_date: $msoBilling->or_date ?? $msoBilling->date,
                payee: $member->full_name,
            );

            if ($msoBilling->type == 1) {
                app(DepositToMsoAccount::class)->handle(MsoType::SAVINGS, $data);
            }
            if ($msoBilling->type == 2) {
                app(DepositToMsoAccount::class)->handle(MsoType::IMPREST, $data);
            }
            if ($msoBilling->type == 3) {
                app(DepositToMsoAccount::class)->handle(MsoType::LOVE_GIFT, $data);
            }

            if ($data->payment_type_id == PaymentTypes::ADA->value) {
                $data->account_id = $cash_in_bank_account_id;
            } else {
                $data->account_id = $cash_on_hand_account_id;
            }
            $data->debit = $payment->amount_paid;
            $data->credit = null;
            app(CreateTransaction::class)->handle($data);

            $payment->update([
                'posted' => true,
            ]);
        });

        $msoBilling->update([
            'posted' => true,
        ]);
        DB::commit();
    }
}
