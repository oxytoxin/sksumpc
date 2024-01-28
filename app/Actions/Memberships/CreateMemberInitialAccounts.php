<?php

namespace App\Actions\Memberships;

use App\Actions\Imprests\CreateNewImprestsAccount;
use App\Actions\LoveGifts\CreateNewLoveGiftsAccount;
use App\Actions\Savings\CreateNewSavingsAccount;
use App\Models\Member;
use App\Oxytoxin\DTO\MSO\Accounts\ImprestAccountData;
use App\Oxytoxin\DTO\MSO\Accounts\LoveGiftAccountData;
use App\Oxytoxin\DTO\MSO\Accounts\SavingsAccountData;
use Lorisleiva\Actions\Concerns\AsAction;

class CreateMemberInitialAccounts
{
    use AsAction;

    public function handle(Member $member)
    {
        app(CreateNewSavingsAccount::class)->handle(new SavingsAccountData(
            member_id: $member->id,
            name: $member->full_name
        ));
        app(CreateNewImprestsAccount::class)->handle(new ImprestAccountData(
            member_id: $member->id,
        ));
        app(CreateNewLoveGiftsAccount::class)->handle(new LoveGiftAccountData(
            member_id: $member->id,
        ));
    }
}
