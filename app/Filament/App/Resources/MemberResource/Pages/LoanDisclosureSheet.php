<?php

namespace App\Filament\App\Resources\MemberResource\Pages;

use App\Filament\App\Pages\Cashier\Reports\HasSignatories;
use App\Filament\App\Resources\MemberResource;
use App\Models\Loan;
use App\Models\User;
use Filament\Resources\Pages\Page;

class LoanDisclosureSheet extends Page
{
    use HasSignatories;

    protected static string $resource = MemberResource::class;

    protected static string $view = 'filament.app.resources.member-resource.pages.loan-disclosure-sheet';

    public Loan $loan;

    protected function getSignatories()
    {
        $bookkeeper = User::whereRelation('roles', 'name', 'book-keeper')->first();
        $loan_officer = User::whereRelation('roles', 'name', 'loan-staff')->first();
        $treasurer = User::whereRelation('roles', 'name', 'treasurer')->first();
        $manager = User::whereRelation('roles', 'name', 'manager')->first();
        $this->signatories = [
            [
                'action' => 'Prepared by:',
                'name' => $loan_officer->name ?? auth()->user()->name,
                'position' => 'Loan Officer',
            ],
            [
                'action' => 'Checked by:',
                'name' => $bookkeeper?->name ?? 'ADRIAN VOLTAIRE POLO',
                'position' => 'Posting Clerk',
            ],
            [
                'action' => 'Received by:',
                'name' => $treasurer?->name ?? 'DESIREE G. LEGASPI',
                'position' => 'Treasurer',
            ],
            [
                'action' => 'Noted:',
                'name' => $manager?->name ?? 'FLORA C. DAMANDAMAN',
                'position' => 'Manager',
            ],
            [
                'action' => 'Conforme:',
                'name' => strtoupper($this->loan->member?->full_name),
                'position' => 'Borrower',
            ],
        ];
    }
}
