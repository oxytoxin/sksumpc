<?php

namespace App\Actions\Savings;

use App\Models\Account;

class GenerateAccountNumber
{
    public function handle($member_type_id)
    {
        $account_number_prefix = match ($member_type_id) {
            1 => '21110-1011-',
            2 => '21110-1012-',
            3 => '21110-1013-',
            default => '21110-1011-',
        };

        return str($account_number_prefix)->append(str_pad((Account::latest('id')->first()?->id ?? 0) + 1, 6, '0', STR_PAD_LEFT))->toString();
    }
}
