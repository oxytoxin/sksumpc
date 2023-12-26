<?php

use App\Models\User;

use function Pest\Laravel\actingAs;

beforeEach(function () {
    actingAs(User::find(1));
});

test('can access dashboard', function () {
    $this->get(route('filament.app.pages.dashboard'))
        ->assertOk();
});
test('can access transactions page', function () {
    $this->get(route('filament.app.pages.transactions-page'))
        ->assertOk();
});
test('can access members page', function () {
    $this->get(route('filament.app.resources.members.index'))
        ->assertOk();
});
test('can access cashier reports page', function () {
    $this->get(route('filament.app.pages.reports'))
        ->assertOk();
});
test('can access loan applications page', function () {
    $this->get(route('filament.app.resources.loan-applications.index'))
        ->assertOk();
});
test('can access approved loan applications page', function () {
    $this->get(route('filament.app.pages.new-loan-from-application'))
        ->assertOk();
});
test('can access disapproved loan applications page', function () {
    $this->get(route('filament.app.pages.disapproved-loan-applications'))
        ->assertOk();
});
test('can access loan billings page', function () {
    $this->get(route('filament.app.resources.loan-billings.index'))
        ->assertOk();
});
test('can access loans page', function () {
    $this->get(route('filament.app.resources.loans.index'))
        ->assertOk();
});
test('can access total loan released page', function () {
    $this->get(route('filament.app.pages.total-loan-released-report'))
        ->assertOk();
});
test('can access capital subscriptions page', function () {
    $this->get(route('filament.app.resources.capital-subscriptions.index'))
        ->assertOk();
});
test('can access capital subscription billings page', function () {
    $this->get(route('filament.app.resources.capital-subscription-billings.index'))
        ->assertOk();
});
test('can access cbu schedule page', function () {
    $this->get(route('filament.app.pages.cbu-schedule'))
        ->assertOk();
});
test('can access cbu schedule summary page', function () {
    $this->get(route('filament.app.pages.share-capital-schedule-summary-report'))
        ->assertOk();
});
test('can access cbu reports page', function () {
    $this->get(route('filament.app.pages.share-capital-reports'))
        ->assertOk();
});
test('can access cash collectibles page', function () {
    $this->get(route('filament.app.resources.cash-collectibles.index'))
        ->assertOk();
});
test('can access loan types page', function () {
    $this->get(route('filament.app.resources.loan-types.index'))
        ->assertOk();
});
test('can access user account management page', function () {
    $this->get(route('filament.app.pages.user-account-management'))
        ->assertOk();
});
test('can access disapproval reasons page', function () {
    $this->get(route('filament.app.resources.disapproval-reasons.index'))
        ->assertOk();
});
test('can access divisions page', function () {
    $this->get(route('filament.app.resources.divisions.index'))
        ->assertOk();
});
test('can access occupations page', function () {
    $this->get(route('filament.app.resources.occupations.index'))
        ->assertOk();
});
test('can access civil statuses page', function () {
    $this->get(route('filament.app.resources.civil-statuses.index'))
        ->assertOk();
});
test('can access religions page', function () {
    $this->get(route('filament.app.resources.religions.index'))
        ->assertOk();
});
test('can access member types page', function () {
    $this->get(route('filament.app.resources.member-types.index'))
        ->assertOk();
});
