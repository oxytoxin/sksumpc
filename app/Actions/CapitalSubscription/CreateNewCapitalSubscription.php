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
                'is_active' => false,
            ]);
            $cbuData = $data->toArray();
            $cbuData['member_id'] = $member->id;
            $cbu = CapitalSubscription::create($cbuData);
            DB::commit();

            return $cbu;
        }
    }
