<?php

use App\Livewire\App\LoansTable;
use App\Models\Loan;
use App\Models\LoanApplication;
use App\Models\Member;
use App\Models\User;
use Filament\Tables\Actions\CreateAction;
use PhpOffice\PhpSpreadsheet\Calculation\Financial\CashFlow\Constant\Periodic\Cumulative;

use function Pest\Laravel\actingAs;
use function Pest\Laravel\assertModelExists;
use function Pest\Livewire\livewire;

it('test loan functions from phpspreadsheet', function () {
});

it('can create a new loan', function () {
    $member = Member::find(663);
    actingAs(User::find(1));
    $la = LoanApplication::create([
        'member_id' => $member->id,
        'loan_type_id' => 1,
        'number_of_terms' => 12,
        'priority_number' => '0000001',
        'desired_amount' => LoanApplication::count() * 10000 + 15000,
        'processor_id' => 1,
        'reference_number' => 'ACL-2023-000001',
        'monthly_payment' => 888.4900,
        'transaction_date' => today(),
        'status' => LoanApplication::STATUS_APPROVED,
        'approvals' => [
            ['name' => 'CATHERINE A. LEGASPI', 'position' => 'Crecom-Secretary'],
            ['name' => 'JACQUILINE B. CANDIDO', 'position' => 'Crecom-Chairperson'],
            ['name' => 'JUVEN LACONSE', 'position' => 'Crecom-Vice Chairperson'],
            ['name' => 'FLORA C. DAMANDAMAN', 'position' => 'Manager'],
        ],
    ]);
    assertModelExists($la);
    livewire(LoansTable::class, ['member' => $member])
        ->callTableAction(name: CreateAction::class, data: ['loan_application_id' => $la->id, 'transaction_date' => today()]);
    $loan = Loan::latest()->first();
    $loan->posted = true;
    $loan->save();
});
