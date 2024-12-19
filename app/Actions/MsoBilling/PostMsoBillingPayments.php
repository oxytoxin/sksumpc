<?php

namespace App\Actions\MsoBilling;

use App\Models\TransactionType;
use Illuminate\Support\Facades\DB;
use Filament\Notifications\Notification;
use App\Actions\CashCollections\PayCashCollectible;
use App\Actions\Imprests\DepositToImprestAccount;
use App\Actions\LoveGifts\DepositToLoveGiftsAccount;
use App\Actions\Savings\DepositToSavingsAccount;
use App\Models\CashCollectibleBilling;
use App\Models\CashCollectibleBillingPayment;
use App\Models\Member;
use App\Models\MsoBilling;
use App\Models\MsoBillingPayment;
use App\Oxytoxin\DTO\CashCollectibles\CashCollectiblePaymentData;
use App\Oxytoxin\DTO\MSO\ImprestData;
use App\Oxytoxin\DTO\MSO\LoveGiftData;
use App\Oxytoxin\DTO\MSO\SavingsData;


class PostMsoBillingPayments
{


    public function handle(MsoBilling $msoBilling)
    {
        if (!$msoBilling->reference_number || !$msoBilling->payment_type_id) {
            return Notification::make()->title('Billing reference number and payment type is missing!')->danger()->send();
        }
        DB::beginTransaction();
        $transaction_type = TransactionType::firstWhere('name', 'CRJ');
        $msoBilling->payments()->with('member')->each(function (MsoBillingPayment $payment) use ($msoBilling, $transaction_type) {
            $member = $payment->member;
            if ($msoBilling->type == 1) {
                app(DepositToSavingsAccount::class)->handle($member, new SavingsData(
                    payment_type_id: $msoBilling->payment_type_id,
                    reference_number: $msoBilling->reference_number,
                    amount: $payment->amount_paid,
                    savings_account_id: $payment->account_id,
                    transaction_date: $msoBilling->date,
                ), $transaction_type);
            }
            if ($msoBilling->type == 2) {
                app(DepositToImprestAccount::class)->handle($member, new ImprestData(
                    payment_type_id: $msoBilling->payment_type_id,
                    reference_number: $msoBilling->reference_number,
                    amount: $payment->amount_paid,
                    transaction_date: $msoBilling->date,
                ), $transaction_type);
            }
            if ($msoBilling->type == 3) {
                app(DepositToLoveGiftsAccount::class)->handle($member, new LoveGiftData(
                    payment_type_id: $msoBilling->payment_type_id,
                    reference_number: $msoBilling->reference_number,
                    amount: $payment->amount_paid,
                    transaction_date: $msoBilling->date,
                ), $transaction_type);
            }
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
