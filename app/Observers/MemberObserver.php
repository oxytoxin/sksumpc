<?php

namespace App\Observers;

use App\Actions\CapitalSubscription\CreateNewCapitalSubscriptionAccount;
use App\Models\Member;
use App\Oxytoxin\DTO\MSO\Accounts\CapitalSubscriptionAccountData;

class MemberObserver
{
    public function updated(Member $member): void
    {
        app(CreateNewCapitalSubscriptionAccount::class)->handle(new CapitalSubscriptionAccountData(
            member_id: $member->id,
            name: $member->full_name ?? ($member->first_name.' '.$member->last_name)
        ));
    }
}
