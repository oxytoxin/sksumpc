<?php

use App\Models\Account;
use App\Models\Member;
use App\Models\SavingsAccount;
use App\Models\User;

use function Pest\Laravel\actingAs;

it('can close a savings account', function () {
    actingAs(User::find(1));
    $member = Member::find(664);
    $member_savings = Account::firstWhere('tag', 'member_savings');

    $savings_account = SavingsAccount::create([
        'name' => 'TEST CLOSE',
        'number' => 99999,
        'account_type_id' => $member_savings->account_type_id,
        'member_id' => $member->id,
        'tag' => 'regular_savings',
    ], $member_savings);

    expect($savings_account->isActive())->toBeTrue();
    expect($savings_account->isClosed())->toBeFalse();

    $savings_account->close('Account closure test');

    expect($savings_account->fresh()->isClosed())->toBeTrue();
    expect($savings_account->fresh()->isActive())->toBeFalse();
    expect($savings_account->fresh()->closed_at)->not->toBeNull();
    expect($savings_account->fresh()->close_remarks)->toBe('Account closure test');

    $savings_account->forceDelete();
});

it('can close a savings account without remarks', function () {
    actingAs(User::find(1));
    $member = Member::find(664);
    $member_savings = Account::firstWhere('tag', 'member_savings');

    $savings_account = SavingsAccount::create([
        'name' => 'TEST CLOSE NO REMARKS',
        'number' => 99998,
        'account_type_id' => $member_savings->account_type_id,
        'member_id' => $member->id,
        'tag' => 'regular_savings',
    ], $member_savings);

    $savings_account->close();

    expect($savings_account->fresh()->isClosed())->toBeTrue();
    expect($savings_account->fresh()->close_remarks)->toBeNull();

    $savings_account->forceDelete();
});

it('excludes closed savings accounts from billing', function () {
    actingAs(User::find(1));
    $member = Member::find(664);
    $member_savings = Account::firstWhere('tag', 'member_savings');

    $savings_account = SavingsAccount::create([
        'name' => 'TEST BILLING',
        'number' => 99997,
        'account_type_id' => $member_savings->account_type_id,
        'member_id' => $member->id,
        'tag' => 'regular_savings',
    ], $member_savings);

    $savings_account->close('Closed before billing');

    $active_accounts = SavingsAccount::whereMemberId($member->id)->active()->get();
    expect($active_accounts->contains($savings_account))->toBeFalse();

    $closed_accounts = SavingsAccount::whereMemberId($member->id)->closed()->get();
    expect($closed_accounts->contains($savings_account))->toBeTrue();

    $savings_account->forceDelete();
});

it('includes active savings accounts in billing', function () {
    actingAs(User::find(1));
    $member = Member::find(664);
    $member_savings = Account::firstWhere('tag', 'member_savings');

    $savings_account = SavingsAccount::create([
        'name' => 'TEST ACTIVE BILLING',
        'number' => 99996,
        'account_type_id' => $member_savings->account_type_id,
        'member_id' => $member->id,
        'tag' => 'regular_savings',
    ], $member_savings);

    $active_accounts = SavingsAccount::whereMemberId($member->id)->active()->get();
    expect($active_accounts->contains($savings_account))->toBeTrue();

    $savings_account->forceDelete();
});
