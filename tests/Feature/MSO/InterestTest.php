<?php

use App\Actions\MSO\DepositToMsoAccount;
use App\Actions\MSO\WithdrawFromMsoAccount;
use App\Actions\Savings\GenerateSavingsInterestForMember;
use App\Enums\MsoType;
use App\Models\Account;
use App\Models\Member;
use App\Models\SavingsAccount;
use App\Models\TransactionType;
use App\Models\User;
use App\Oxytoxin\DTO\Transactions\TransactionData;
use Illuminate\Support\Str;

use function Pest\Laravel\actingAs;
use function Pest\Laravel\travelTo;
use function PHPUnit\Framework\assertEquals;

it('can accrue quarterly interest', function () {
    travelTo(date_create('12/31/2022'));
    actingAs(User::find(1));
    $member = Member::find(664);
    $member_savings = Account::firstWhere('tag', 'member_savings');

    $savings_account = SavingsAccount::create([
        'name' => 'DEFAULT',
        'number' => 12345,
        'account_type_id' => $member_savings->account_type_id,
        'member_id' => $member->id,
        'tag' => 'regular_savings',
    ], $member_savings);

    $data = new TransactionData(
        account_id: $savings_account->id,
        payment_type_id: 1,
        reference_number: Str::random(),
        credit: 5543.49,
        transaction_date: today(),
        transactionType: TransactionType::CRJ(),
        member_id: $member->id,
        payee: $member->full_name,
    );
    app(DepositToMsoAccount::class)->handle(MsoType::SAVINGS, $data);
    assertEquals(round($savings_account->savings()->sum('amount'), 2), 5543.49);

    travelTo(date_create('01/31/2023'));
    $data->transaction_date = today();
    $data->credit = 500;
    app(DepositToMsoAccount::class)->handle(MsoType::SAVINGS, $data);
    assertEquals(round($savings_account->savings()->sum('amount'), 2), 6043.49);

    travelTo(date_create('03/03/2023'));
    $data->transaction_date = today();
    $data->credit = 500;
    app(DepositToMsoAccount::class)->handle(MsoType::SAVINGS, $data);
    assertEquals(round($savings_account->savings()->sum('amount'), 2), 6543.49);

    travelTo(date_create('03/31/2023'));
    app(GenerateSavingsInterestForMember::class)->handle($member);
    assertEquals(round($savings_account->savings()->sum('amount'), 2), 6558.35);

    travelTo(date_create('04/05/2023'));
    $data->transaction_date = today();
    $data->credit = 400;
    app(DepositToMsoAccount::class)->handle(MsoType::SAVINGS, $data);
    assertEquals(round($savings_account->savings()->sum('amount'), 2), 6958.35);

    travelTo(date_create('04/11/2023'));
    $data->transaction_date = today();
    $data->debit = 5000;
    app(WithdrawFromMsoAccount::class)->handle(MsoType::SAVINGS, $data);
    assertEquals(round($savings_account->savings()->sum('amount'), 2), 1958.35);

    travelTo(date_create('05/04/2023'));
    $data->transaction_date = today();
    $data->debit = 400;
    app(DepositToMsoAccount::class)->handle(MsoType::SAVINGS, $data);
    assertEquals(round($savings_account->savings()->sum('amount'), 2), 2358.35);
});
