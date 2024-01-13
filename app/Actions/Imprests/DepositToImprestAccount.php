<?php

namespace App\Actions\Imprests;

use App\Models\Imprest;
use App\Models\Member;
use App\Oxytoxin\DTO\MSO\ImprestData;
use App\Oxytoxin\ImprestsProvider;
use Lorisleiva\Actions\Concerns\AsAction;

class DepositToImprestAccount
{
    use AsAction;

    public function handle(Member $member, ImprestData $data)
    {
        return Imprest::create([
            'payment_type_id' => $data->payment_type_id,
            'reference_number' => $data->reference_number,
            'amount' => $data->amount,
            'interest_rate' => ImprestsProvider::INTEREST_RATE,
            'member_id' => $member->id,
            'transaction_date' => $data->transaction_date,
        ]);
    }
}
