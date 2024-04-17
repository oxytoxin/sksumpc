<?php

namespace Database\Seeders;

use App\Actions\Loans\ImportExistingLoan;
use App\Models\LoanApplication;
use App\Models\LoanType;
use App\Models\Member;
use DB;
use Illuminate\Database\Seeder;
use Spatie\SimpleExcel\SimpleExcelReader;

class ImportExistingLoansSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::beginTransaction();
        $this->importLoansFromFile('REGULAR-LOAN.xlsx', LoanType::find(1));
        $this->importLoansFromFile('EMERGENCY-LOAN.xlsx', LoanType::find(2));
        $this->importLoansFromFile('PROVIDENTIAL-LOAN.xlsx', LoanType::find(3));
        $this->importLoansFromFile('COMMODITY-LOAN.xlsx', LoanType::find(4));
        $this->importLoansFromFile('KABUHAYAN-LOAN.xlsx', LoanType::find(5));
        $this->importLoansFromFile('SPECIAL-LOAN.xlsx', LoanType::find(6));
        $this->importLoansFromFile('FLY-NOW-PAY-LATER-LOAN.xlsx', LoanType::find(7));
        $this->importLoansFromFile('RES-REGULAR-LOAN.xlsx', LoanType::find(11));
        $this->importLoansFromFile('LBP-REGULAR-LOAN.xlsx', LoanType::find(10));
        $this->importLoansFromFile('RES-LBP-COMMODITY-LOAN.xlsx', LoanType::find(15));
        LoanApplication::query()->update([
            'status' => LoanApplication::STATUS_POSTED
        ]);
        DB::commit();
    }

    protected function importLoansFromFile(string $filename, LoanType $loanType)
    {
        $reader = SimpleExcelReader::create(storage_path('csv/loans/' . $filename), 'xlsx');
        $members_code = $reader->getRows()->pluck('MEMBERS ID');
        $members = Member::whereIn('mpc_code', $members_code->all())->get();
        $reader->getRows()->each(function ($data) use ($members, $loanType) {
            try {
                if (filled($data['MEMBERS ID'])) {
                    app(ImportExistingLoan::class)->handle(
                        member: $members->firstWhere('mpc_code', $data['MEMBERS ID']),
                        loanType: $loanType,
                        reference_number: $data['REFERENCE NUMBER'],
                        amount: $data['DESIRED AMOUNT'],
                        balance_forwarded: $data['BALANCE TO FORWARD'],
                        number_of_terms: $data['NUMBER OF TERMS'],
                        application_date: $data['DATE APPLIED'],
                    );
                }
            } catch (\Throwable $th) {
                dd($data, $th);
            }
        });
    }
}
