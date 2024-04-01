<?php

namespace App\Actions\Memberships;

use App\Actions\CapitalSubscription\CreateNewCapitalSubscriptionAccount;
use App\Actions\Imprests\CreateNewImprestsAccount;
use App\Actions\LoveGifts\CreateNewLoveGiftsAccount;
use App\Actions\Savings\CreateNewSavingsAccount;
use App\Models\Member;
use App\Models\User;
use App\Oxytoxin\DTO\MSO\Accounts\CapitalSubscriptionAccountData;
use App\Oxytoxin\DTO\MSO\Accounts\ImprestAccountData;
use App\Oxytoxin\DTO\MSO\Accounts\LoveGiftAccountData;
use App\Oxytoxin\DTO\MSO\Accounts\SavingsAccountData;
use Lorisleiva\Actions\Concerns\AsAction;
use Spatie\Permission\Models\Role;

class CreateMemberInitialAccounts
{
    use AsAction;

    public function handle(Member $member)
    {
        app(CreateNewCapitalSubscriptionAccount::class)->handle(new CapitalSubscriptionAccountData(
            member_id: $member->id,
            name: $member->full_name ?? ($member->first_name.' '.$member->last_name)
        ));
        app(CreateNewSavingsAccount::class)->handle(new SavingsAccountData(
            member_id: $member->id,
            name: $member->full_name ?? ($member->first_name.' '.$member->last_name)
        ));
        app(CreateNewImprestsAccount::class)->handle(new ImprestAccountData(
            member_id: $member->id,
            name: $member->full_name ?? ($member->first_name.' '.$member->last_name)
        ));
        app(CreateNewLoveGiftsAccount::class)->handle(new LoveGiftAccountData(
            member_id: $member->id,
            name: $member->full_name ?? ($member->first_name.' '.$member->last_name)
        ));

        $user = User::create([
            'member_id' => $member->id,
            'name' => $member->full_name,
            'email' => str($member->mpc_code)->lower()->append('@gmail.com'),
            'password' => 'password',
        ]);
        $user->assignRole(Role::firstWhere('name', 'member'));
    }
}
