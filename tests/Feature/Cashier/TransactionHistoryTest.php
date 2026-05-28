<?php

use App\Models\CashierTransaction;
use App\Models\User;

use function Pest\Laravel\actingAs;
use function Pest\Laravel\get;

beforeEach(function () {
    actingAs(User::find(1));
});

it('can access transaction history page', function () {
    get(route('filament.app.pages.transaction-history-page'))
        ->assertOk();
});

it('creates cashier transaction record with receipt data', function () {
    $transactions = [
        [
            'account_number' => '101',
            'account_name' => 'Test Account',
            'reference_number' => 'REF001',
            'amount' => 1000,
            'payment_type' => 'Cash',
            'payee' => 'John Doe',
            'remarks' => 'OTHER PAYMENTS',
        ],
    ];

    $record = CashierTransaction::create([
        'user_id' => auth()->id(),
        'member_id' => null,
        'transaction_date' => '2026-05-28',
        'transactions' => $transactions,
    ]);

    expect($record->transactions)->toBe($transactions);
    expect($record->user_id)->toBe(auth()->id());
    expect($record->transaction_date->format('Y-m-d'))->toBe('2026-05-28');
});

it('stores multiple transaction items in json column', function () {
    $transactions = [
        ['account_number' => '101', 'account_name' => 'Test', 'reference_number' => 'REF001', 'amount' => 1000, 'payment_type' => 'Cash', 'payee' => 'John Doe', 'remarks' => 'TEST'],
        ['account_number' => '102', 'account_name' => 'Test 2', 'reference_number' => 'REF002', 'amount' => 500, 'payment_type' => 'Cash', 'payee' => 'Jane Doe', 'remarks' => 'TEST'],
    ];

    $record = CashierTransaction::create([
        'user_id' => auth()->id(),
        'member_id' => null,
        'transaction_date' => '2026-05-28',
        'transactions' => $transactions,
    ]);

    expect($record->transactions)->toHaveCount(2);
    expect(collect($record->transactions)->sum('amount'))->toBe(1500);
});
