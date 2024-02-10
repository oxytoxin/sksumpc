<?php

namespace App\Oxytoxin\DTO\MSO\Accounts;

use App\Models\Account;
use App\Models\Member;
use Spatie\LaravelData\Data;

class SavingsAccountData extends Data
{

    public function __construct(
        public int $member_id,
        public string $name,
        public ?string $number = null,
    ) {
        $member = Member::find($this->member_id);
        if (!$this->number) {
            $account_number_prefix = match ($member->member_type_id) {
                1 => '21110-1011-',
                2 => '21110-1011-',
                3 => '21110-1012-',
                4 => '21110-1013-',
                default => '21110-1011-',
            };
            $this->number =  str($account_number_prefix)->append(str_pad((Account::latest('id')->first()?->id ?? 0) + 1, 6, '0', STR_PAD_LEFT));
        }
    }
}
