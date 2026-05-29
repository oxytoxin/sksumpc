<?php

use App\Models\SavingsAccount;
use App\Models\User;

use function Pest\Laravel\actingAs;

beforeEach(function () {
    actingAs(User::find(1));
});

test('can access closed savings report page', function () {
    $this->get(route('filament.app.pages.closed-savings-report'))
        ->assertOk();
});

it('displays closed savings accounts', function () {
    $account = SavingsAccount::closed()->first();

    $response = $this->get(route('filament.app.pages.closed-savings-report'));
    $response->assertOk();

    if ($account) {
        $response->assertSee($account->number);
    }
});
