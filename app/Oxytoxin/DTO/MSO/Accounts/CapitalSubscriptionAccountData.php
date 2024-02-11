<?php

namespace App\Oxytoxin\DTO\MSO\Accounts;

use App\Models\Account;
use App\Models\Member;
use Spatie\LaravelData\Data;

class CapitalSubscriptionAccountData extends Data
{
    public function __construct(
        public int $member_id,
        public string $name,
        public ?Account $parent = null,
        public ?string $number = null,
    ) {
        $member = Member::find($this->member_id);
        if (! $this->parent) {
            $this->parent = match ($member->member_type_id) {
                1 => Account::whereNull('member_id')->whereTag('member_common_cbu_paid')->first(),
                2 => Account::whereNull('member_id')->whereTag('member_common_cbu_paid')->first(),
                3 => Account::whereNull('member_id')->whereTag('member_preferred_cbu_paid')->first(),
                4 => Account::whereNull('member_id')->whereTag('member_laboratory_cbu_paid')->first(),
                default => Account::whereNull('member_id')->whereTag('member_common_cbu_paid')->first(),
            };
        }
        if (! $this->number) {
            $this->number = str($this->parent->number)->append('-')->append(str_pad((Account::latest('id')->first()?->id ?? 0) + 1, 6, '0', STR_PAD_LEFT));
        }
    }
}
