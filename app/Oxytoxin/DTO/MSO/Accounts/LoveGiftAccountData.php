<?php

namespace App\Oxytoxin\DTO\MSO\Accounts;

use App\Models\Account;
use App\Models\Member;
use Spatie\LaravelData\Data;

class LoveGiftAccountData extends Data
{

    public function __construct(
        public int $member_id,
        public string $name,
        public ?string $number = null
    ) {
        if (!$this->number) {
            $this->number =  str('21110-1016-')->append(str_pad((Account::latest('id')->first()?->id ?? 0) + 1, 6, '0', STR_PAD_LEFT));
        }
    }
}
