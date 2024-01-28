<?php

namespace App\Oxytoxin\DTO\MSO\Accounts;

use App\Models\LoveGiftAccount;
use App\Models\Member;
use Spatie\LaravelData\Data;

class LoveGiftAccountData extends Data
{

    public function __construct(
        public int $member_id,
        public ?string $number = null
    ) {
        $member = Member::find($this->member_id);
        if (!$this->number) {
            $this->number =  str('21110-1016-')->append(str_pad((LoveGiftAccount::latest('id')->first()?->id ?? 0) + 1, 6, '0', STR_PAD_LEFT));
        }
    }
}
