<?php

namespace App\Filament\App\Resources\MemberResource\Pages;

use App\Filament\App\Resources\MemberResource;
use App\Oxytoxin\ShareCapitalProvider;
use DB;
use Filament\Resources\Pages\CreateRecord;
use Illuminate\Database\Eloquent\Model;

class CreateMember extends CreateRecord
{
    protected static string $resource = MemberResource::class;

    protected function handleRecordCreation(array $data): Model
    {
        [
            'number_of_terms' => $number_of_terms,
            'number_of_shares' => $number_of_shares,
            'amount_subscribed' => $amount_subscribed,
            'code' => $code,
        ] = $data;
        unset($data['number_of_terms'], $data['number_of_shares'], $data['initial_amount_paid'], $data['amount_subscribed'], $data['code']);
        DB::beginTransaction();
        $member = static::getModel()::create($data);
        $cbu = $member->capital_subscriptions()->create([
            'number_of_terms' => $number_of_terms,
            'number_of_shares' => $number_of_shares,
            'amount_subscribed' => $amount_subscribed,
            'code' => $code,
            'outstanding_balance' => $amount_subscribed,
            'is_common' => true,
            'transaction_date' => today(),
            'par_value' => ShareCapitalProvider::PAR_VALUE,
        ]);
        DB::commit();
        return $member;
    }
}
