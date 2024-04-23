<?php

namespace App\Actions\CashCollectionBilling;

use App\Models\CashCollectibleBilling;
use App\Models\Member;
use DB;
use Lorisleiva\Actions\Concerns\AsAction;

class CreateIndividualBilling
{
    use AsAction;

    public function handle($payment_type_id, $cash_collectible_id, $date, $member_id, $payee, $amount)
    {
        DB::beginTransaction();

        $member = Member::find($member_id);

        $billing = CashCollectibleBilling::forceCreateQuietly([
            'cash_collectible_id' => $cash_collectible_id,
            'payment_type_id' => $payment_type_id,
            'date' => $date,
            'cashier_id' => auth()->id()
        ]);

        $billing->reference_number = $billing->generateReferenceNumber($billing);
        $billing->save();

        $billing->cash_collectible_billing_payments()->create([
            'cash_collectible_id' => $billing->cash_collectible_id,
            'member_id' => $member->id,
            'payee' => $payee,
            'amount_due' => $amount,
            'amount_paid' => $amount,
        ]);

        DB::commit();
    }
}
