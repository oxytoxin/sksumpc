<?php

use App\Livewire\Bookkeeper\BookkeeperLoanAgingTab;
use App\Models\Loan;
use App\Models\User;
use Illuminate\Foundation\Testing\DatabaseTransactions;

use function Pest\Laravel\actingAs;
use function Pest\Laravel\get;
use function Pest\Livewire\livewire;

uses(DatabaseTransactions::class);

beforeEach(function (): void {
    actingAs(User::query()->firstOrFail());
    config()->set('app.transaction_date', '2099-01-15');
});

it('allows access to the overdue and matured loan report page', function (): void {
    get(route('filament.app.pages.overdue-matured-loan-report'))
        ->assertOk()
        ->assertSee('Overdue / Matured Loan Report');
});

it('includes loans that mature on the selected date', function (): void {
    $loan = Loan::query()->firstOrFail();
    $referenceNumber = 'MATURITY-DATE-PICKER-TEST';

    Loan::withoutEvents(fn () => $loan->update([
        'reference_number' => $referenceNumber,
        'maturity_date' => '2099-01-15',
        'outstanding_balance' => '1000.00',
        'posted' => true,
    ]));

    livewire(BookkeeperLoanAgingTab::class)
        ->set('data.to_date', '2099-01-15')
        ->assertSee($referenceNumber)
        ->set('data.to_date', '2099-01-14')
        ->assertDontSee($referenceNumber);
});
