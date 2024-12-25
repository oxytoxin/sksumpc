<?php

namespace App\Actions\CapitalSubscription;

use App\Models\CapitalSubscription;
use App\Models\Member;
use App\Oxytoxin\DTO\CapitalSubscription\CapitalSubscriptionData;
use Illuminate\Support\Facades\DB;

class CreateNewCapitalSubscription
{
    public function handle(Member $member, CapitalSubscriptionData $data): CapitalSubscription
    {
        DB::beginTransaction();
        $member->capital_subscriptions()->update([
            'is_common' => false,
        ]);
        $cbu = $member->capital_subscriptions()->create($data->toArray());
        DB::commit();

        return $cbu;
    }
}
