<?php

namespace App\Filament\App\Resources\MemberResource\Pages;

use App\Actions\Memberships\CreateMemberInitialAccounts;
use App\Filament\App\Pages\Cashier\RequiresBookkeeperTransactionDate;
use App\Filament\App\Resources\MemberResource;
use App\Models\MemberType;
use DB;
use Filament\Resources\Pages\CreateRecord;
use Illuminate\Database\Eloquent\Model;

class CreateMember extends CreateRecord
{
    use RequiresBookkeeperTransactionDate;
    
    protected static string $resource = MemberResource::class;

    protected function handleRecordCreation(array $data): Model
    {
        [
            'number_of_terms' => $number_of_terms,
            'number_of_shares' => $number_of_shares,
            'amount_subscribed' => $amount_subscribed,
        ] = $data;
        unset($data['number_of_terms'], $data['number_of_shares'], $data['initial_amount_paid'], $data['amount_subscribed'], $data['code']);
        DB::beginTransaction();
        $member = static::getModel()::create($data);
        $initial_amount_paid = MemberType::find($data['member_type_id'])->minimum_initial_payment;
        $monthly_payment = ($amount_subscribed - $initial_amount_paid) / $number_of_terms;
        $cbu = $member->capital_subscriptions()->create([
            'monthly_payment' => $monthly_payment,
            'initial_amount_paid' => $initial_amount_paid,
            'number_of_terms' => $number_of_terms,
            'number_of_shares' => $number_of_shares,
            'amount_subscribed' => $amount_subscribed,
            'outstanding_balance' => $amount_subscribed,
            'is_common' => true,
            'transaction_date' => today(),
            'par_value' => $member->member_type->par_value,
        ]);
        app(CreateMemberInitialAccounts::class)->handle($member);
        DB::commit();

        return $member;
    }
}
