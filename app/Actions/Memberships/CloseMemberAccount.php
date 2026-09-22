<?php

namespace App\Actions\Memberships;

use App\Models\Member;
use App\Models\MembershipStatus;
use Carbon\CarbonInterface;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;

class CloseMemberAccount
{
    public function handle(
        Member $member,
        string $bodResolution,
        CarbonInterface|string $terminationDate,
        string $voucherNumber,
        float $capitalAmount,
    ): MembershipStatus {
        if ($member->terminated_at) {
            throw ValidationException::withMessages([
                'member_id' => 'This account is already closed.',
            ]);
        }

        return DB::transaction(function () use (
            $member,
            $bodResolution,
            $terminationDate,
            $voucherNumber,
            $capitalAmount,
        ): MembershipStatus {
            $membershipStatus = MembershipStatus::create([
                'member_id' => $member->id,
                'type' => MembershipStatus::TERMINATION,
                'bod_resolution' => $bodResolution,
                'effectivity_date' => $terminationDate,
                'termination_voucher_number' => $voucherNumber,
                'capital_amount_closed' => $capitalAmount,
            ]);

            $member->accounts()->active()->update([
                'closed_at' => $terminationDate,
                'close_remarks' => 'WDL OF MEMBERSHIP - '.$voucherNumber,
            ]);
            $member->update(['terminated_at' => $terminationDate]);

            return $membershipStatus;
        });
    }
}
