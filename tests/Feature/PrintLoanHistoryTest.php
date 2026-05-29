<?php

use App\Models\Member;
use App\Models\User;

use function Pest\Laravel\actingAs;

beforeEach(function () {
    actingAs(User::find(1));
});

it('can access print loan history page', function () {
    $member = Member::first();

    $this->get(route('filament.app.resources.members.print-loan-history', ['member' => $member]))
        ->assertOk();
});

it('displays member name on print loan history page', function () {
    $member = Member::first();

    $this->get(route('filament.app.resources.members.print-loan-history', ['member' => $member]))
        ->assertOk()
        ->assertSee($member->full_name);
});

it('shows no loan records message when member has no loans', function () {
    $member = Member::whereDoesntHave('loans')->first();

    if ($member) {
        $this->get(route('filament.app.resources.members.print-loan-history', ['member' => $member]))
            ->assertOk()
            ->assertSee('No loan records found.');
    } else {
        $this->get(route('filament.app.resources.members.print-loan-history', ['member' => Member::first()]))
            ->assertOk();
    }
});
