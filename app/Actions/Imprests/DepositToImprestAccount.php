<?php

namespace App\Actions\Imprests;

use App\Models\Imprest;
use App\Models\Member;
use App\Models\TransactionType;
use App\Oxytoxin\DTO\MSO\ImprestData;
use App\Oxytoxin\Providers\ImprestsProvider;
use DB;
use Lorisleiva\Actions\Concerns\AsAction;

class DepositToImprestAccount
{
    use AsAction;

    public function handle(Member $member, ImprestData $data, TransactionType $transactionType)
    {
        DB::beginTransaction();
        $imprest_account = $member->imprest_account;
        $imprest = Imprest::create([
            'payment_type_id' => $data->payment_type_id,
            'reference_number' => $data->reference_number,
            'amount' => $data->amount,
            'interest_rate' => ImprestsProvider::INTEREST_RATE,
            'member_id' => $member->id,
            'transaction_date' => $data->transaction_date,
        ]);
        $imprest_account->transactions()->create([
            'transaction_type_id' => $transactionType->id,
            'reference_number' => $imprest->reference_number,
            'credit' => $imprest->amount,
        ]);
        DB::commit();

        return $imprest;
    }
}
