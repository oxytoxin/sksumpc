<?php

namespace App\Oxytoxin\DTO\MSO\Accounts;

use App\Actions\Savings\GenerateAccountNumber;
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
        if (! $this->number) {
            $this->number = app(GenerateAccountNumber::class)->handle(member_type_id: $member->member_type_id);
        }
    }
}
