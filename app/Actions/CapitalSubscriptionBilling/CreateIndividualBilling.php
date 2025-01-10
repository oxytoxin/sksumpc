<?php

namespace App\Actions\CapitalSubscriptionBilling;

use App\Models\CapitalSubscriptionBilling;
use App\Models\Member;
use DB;

class CreateIndividualBilling
{
    public function handle($payment_type_id, $date, $member_id)
    {
        DB::beginTransaction();

        $member = Member::find($member_id);
        $active_cbu = $member->active_capital_subscription;

        if (! $active_cbu) {
            abort(403, 'No active CBU for member!');
        }

        $billing = CapitalSubscriptionBilling::forceCreateQuietly([
            'payment_type_id' => $payment_type_id,
            'date' => $date,
            'cashier_id' => auth()->id(),
        ]);

        $billing->reference_number = $billing->generateReferenceNumber($billing);
        $billing->save();

        $amount_due = $active_cbu->payments()->exists() ? $active_cbu->monthly_payment : $active_cbu->initial_amount_paid;

        $billing->capital_subscription_billing_payments()->create([
            'member_id' => $member->id,
            'capital_subscription_id' => $active_cbu->id,
            'amount_due' => $amount_due,
            'amount_paid' => $amount_due,
        ]);
        DB::commit();
    }
}
