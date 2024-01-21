<?php

namespace App\Actions\CapitalSubscription;

use App\Models\Member;
use Illuminate\Support\Facades\DB;
use App\Models\CapitalSubscription;
use Lorisleiva\Actions\Concerns\AsAction;
use App\Oxytoxin\DTO\CapitalSubscription\CapitalSubscriptionData;

class CreateNewCapitalSubscription
{
    use AsAction;

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
