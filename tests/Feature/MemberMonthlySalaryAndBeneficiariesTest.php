<?php

use App\Models\Member;
use App\Models\User;
use App\Oxytoxin\DTO\Membership\MemberBeneficiary;

it('can store and retrieve monthly salary on credit and background', function () {
    $member = Member::find(1);

    $member->credit_and_background()->updateOrCreate(
        ['member_id' => $member->id],
        ['monthly_salary' => 25000.50],
    );

    expect($member->refresh()->credit_and_background->monthly_salary)->toBeString('25000.50');
});

it('can store and retrieve beneficiaries as data collection', function () {
    $member = Member::find(1);

    $beneficiaries = [
        ['name' => 'JUAN DELA CRUZ', 'dob' => '1990-01-15', 'relationship' => 'SON'],
        ['name' => 'MARIA DELA CRUZ', 'dob' => '1992-06-20', 'relationship' => 'DAUGHTER'],
    ];

    $member->update(['beneficiaries' => $beneficiaries]);

    $refreshed = $member->refresh();
    expect($refreshed->beneficiaries)->toHaveCount(2);
    expect($refreshed->beneficiaries->first())->toBeInstanceOf(MemberBeneficiary::class);
    expect($refreshed->beneficiaries->first()->name)->toBe('JUAN DELA CRUZ');
    expect($refreshed->beneficiaries->first()->relationship)->toBe('SON');
    expect($refreshed->beneficiaries->last()->name)->toBe('MARIA DELA CRUZ');
});

it('has beneficiaries_count virtual column', function () {
    $member = Member::find(1);

    $member->update(['beneficiaries' => [
        ['name' => 'TEST ONE', 'dob' => null, 'relationship' => 'SON'],
        ['name' => 'TEST TWO', 'dob' => null, 'relationship' => 'DAUGHTER'],
        ['name' => 'TEST THREE', 'dob' => null, 'relationship' => 'BROTHER'],
    ]]);

    expect($member->refresh()->beneficiaries_count)->toBe(3);
});

it('can access member view page with credit and background fields', function () {
    $this->actingAs(User::find(1));

    $member = Member::find(1);
    $member->credit_and_background()->updateOrCreate(
        ['member_id' => $member->id],
        ['monthly_salary' => 30000],
    );
    $member->update([
        'beneficiaries' => [
            ['name' => 'BEN ONE', 'dob' => '1985-03-10', 'relationship' => 'WIFE'],
        ],
    ]);

    $this->get(route('filament.app.resources.members.view', ['record' => $member]))
        ->assertOk();
});

it('can access member edit page with credit and background fields', function () {
    $this->actingAs(User::find(1));

    $member = Member::find(1);

    $this->get(route('filament.app.resources.members.edit', ['record' => $member]))
        ->assertOk();
});
