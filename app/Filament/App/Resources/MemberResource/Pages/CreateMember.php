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
            'initial_amount_paid' => $initial_amount_paid,
            'amount_subscribed' => $amount_subscribed,
            'code' => $code,
        ] = $data;
        unset($data['number_of_terms'], $data['number_of_shares'], $data['initial_amount_paid'], $data['amount_subscribed'], $data['code']);
        DB::beginTransaction();
        $member = static::getModel()::create($data);
        $cbu = $member->capital_subscriptions()->createQuietly([
            'number_of_terms' => $number_of_terms,
            'number_of_shares' => $number_of_shares,
            'initial_amount_paid' => $initial_amount_paid,
            'amount_subscribed' => $amount_subscribed,
            'code' => $code,
            'outstanding_balance' => 25000,
            'is_common' => true,
            'transaction_date' => today(),
            'par_value' => ShareCapitalProvider::PAR_VALUE,
        ]);
        $cbu->payments()->create([
            'amount' => 0,
            'reference_number' => '#ORIGINALAMOUNT',
            'type' => 'OR',
            'transaction_date' => today(),
        ]);
        $cbu->payments()->create([
            'amount' => $cbu->initial_amount_paid,
            'reference_number' => '#INITIALAMOUNTPAID',
            'type' => 'OR',
            'transaction_date' => today(),
        ]);
        DB::commit();
        return $member;
    }
}
