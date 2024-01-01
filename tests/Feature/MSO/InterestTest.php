<?php

use App\Actions\Savings\DepositToSavingsAccount;
use App\Actions\Savings\GenerateSavingsInterestForMember;
use App\Models\Member;
use App\Models\Saving;
use App\Models\User;
use App\Oxytoxin\DTO\MSO\SavingsData;

use function Pest\Laravel\actingAs;
use function Pest\Laravel\travelTo;

it('can accrue quarterly interest', function () {
    travelTo(date_create('12/31/2022'));
    actingAs(User::find(1));
    $member = Member::find(664);
    $savings_account = $member->savings_accounts()->create([
        'name' => 'Default',
        'number' => 12345
    ]);
    $data = new SavingsData(
        payment_type_id: 1,
        reference_number: Str::random(),
        amount: 5543.49,
        transaction_date: today(),
        savings_account_id: $savings_account->id,
    );
    DepositToSavingsAccount::run($member, $data);

    travelTo(date_create('01/31/2023'));
    $data->transaction_date = today();
    $data->amount = 500;
    DepositToSavingsAccount::run($member, $data);

    travelTo(date_create('03/03/2023'));
    $data->transaction_date = today();
    $data->amount = 500;
    DepositToSavingsAccount::run($member, $data);

    travelTo(date_create('03/31/2023'));
    GenerateSavingsInterestForMember::run($member);
    dd(Saving::get());
});
