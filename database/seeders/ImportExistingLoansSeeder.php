<?php

namespace Database\Seeders;

use App\Actions\Loans\ImportExistingLoan;
use App\Models\LoanType;
use App\Models\Member;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Spatie\SimpleExcel\SimpleExcelReader;

class ImportExistingLoansSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $loanType = LoanType::find(4);
        $reader = SimpleExcelReader::create(storage_path('csv/loans/COMMODITY-LOAN.xlsx'), 'xlsx');
        $members_code = $reader->getRows()->pluck('MEMBERS ID');
        $members = Member::whereIn('mpc_code', $members_code->all())->get();
        $reader->getRows()->each(function ($data) use ($members, $loanType) {
            app(ImportExistingLoan::class)->handle(
                member: $members->firstWhere('mpc_code', $data['MEMBERS ID']),
                loanType: $loanType,
                reference_number: $data['REFERENCE NUMBER'],
                amount: $data['DESIRED AMOUNT'],
                balance_forwarded: $data['BALANCE TO FORWARD'],
                number_of_terms: $data['NUMBER OF TERMS'],
                application_date: $data['DATE APPLIED'],
            );
        });
    }
}
