<?php

namespace App\Actions\CashCollectionBilling;

use App\Models\CashCollectibleBilling;
use App\Models\Member;
use DB;


class CreateIndividualBilling
{


    public function handle($payment_type_id, $account_id, $date, $member_id, $payee, $amount)
    {
        DB::beginTransaction();

        $member = Member::find($member_id);

        $billing = CashCollectibleBilling::forceCreateQuietly([
            'account_id' => $account_id,
            'payment_type_id' => $payment_type_id,
            'date' => $date,
            'cashier_id' => auth()->id()
        ]);

        $billing->reference_number = $billing->generateReferenceNumber($billing);
        $billing->save();

        $billing->cash_collectible_billing_payments()->create([
            'account_id' => $billing->account_id,
            'member_id' => $member->id,
            'payee' => $payee,
            'amount_due' => $amount,
            'amount_paid' => $amount,
        ]);

        DB::commit();
    }
}
