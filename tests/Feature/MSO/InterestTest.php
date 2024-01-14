<?php

use App\Actions\Savings\DepositToSavingsAccount;
use App\Actions\Savings\GenerateSavingsInterestForMember;
use App\Actions\Savings\WithdrawFromSavingsAccount;
use App\Models\Member;
use App\Models\User;
use App\Oxytoxin\DTO\MSO\SavingsData;

use function Pest\Laravel\actingAs;
use function Pest\Laravel\travelTo;
use function PHPUnit\Framework\assertEquals;

it('can accrue quarterly interest', function () {
    travelTo(date_create('12/31/2022'));
    actingAs(User::find(1));
    $member = Member::find(664);
    $savings_account = $member->savings_accounts()->create([
        'name' => 'Default',
        'number' => 12345,
    ]);
    $data = new SavingsData(
        payment_type_id: 1,
        reference_number: Str::random(),
        amount: 5543.49,
        transaction_date: today(),
        savings_account_id: $savings_account->id,
    );
    app(DepositToSavingsAccount::class)->handle($member, $data);
    assertEquals(round($savings_account->savings()->sum('amount'), 2), 5543.49);

    travelTo(date_create('01/31/2023'));
    $data->transaction_date = today();
    $data->amount = 500;
    app(DepositToSavingsAccount::class)->handle($member, $data);
    assertEquals(round($savings_account->savings()->sum('amount'), 2), 6043.49);

    travelTo(date_create('03/03/2023'));
    $data->transaction_date = today();
    $data->amount = 500;
    app(DepositToSavingsAccount::class)->handle($member, $data);
    assertEquals(round($savings_account->savings()->sum('amount'), 2), 6543.49);

    travelTo(date_create('03/31/2023'));
    GenerateSavingsInterestForMember::run($member);
    assertEquals(round($savings_account->savings()->sum('amount'), 2), 6558.35);

    travelTo(date_create('04/05/2023'));
    $data->transaction_date = today();
    $data->amount = 400;
    app(DepositToSavingsAccount::class)->handle($member, $data);
    assertEquals(round($savings_account->savings()->sum('amount'), 2), 6958.35);

    travelTo(date_create('04/11/2023'));
    $data->transaction_date = today();
    $data->amount = 5000;
    app(WithdrawFromSavingsAccount::class)->handle($member, $data);
    assertEquals(round($savings_account->savings()->sum('amount'), 2), 1958.35);

    travelTo(date_create('05/04/2023'));
    $data->transaction_date = today();
    $data->amount = 400;
    app(DepositToSavingsAccount::class)->handle($member, $data);
    assertEquals(round($savings_account->savings()->sum('amount'), 2), 2358.35);
});
