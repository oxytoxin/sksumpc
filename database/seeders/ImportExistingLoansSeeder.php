<?php

namespace Database\Seeders;

use App\Actions\Loans\ImportExistingLoan;
use App\Models\LoanType;
use App\Models\Member;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class ImportExistingLoansSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $loanType = LoanType::find(4);
        app(ImportExistingLoan::class)->handle(
            member: Member::find(4),
            loanType: $loanType,
            reference_number: 1,
            amount: 150000,
            balance_forwarded: 94658.56,
            number_of_terms: 48,
            application_date: '11/10/2021',
        );
        app(ImportExistingLoan::class)->handle(
            member: Member::find(7),
            loanType: $loanType,
            reference_number: 1,
            amount: 150000,
            balance_forwarded: 68902.67,
            number_of_terms: 48,
            application_date: '7/17/2021',
        );
        app(ImportExistingLoan::class)->handle(
            member: Member::find(8),
            loanType: $loanType,
            reference_number: 1,
            amount: 150000,
            balance_forwarded: 71377.56,
            number_of_terms: 48,
            application_date: '7/17/2021',
        );
    }
}
