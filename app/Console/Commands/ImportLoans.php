<?php

namespace App\Console\Commands;

use App\Models\Member;
use Illuminate\Console\Command;
use App\Actions\Loans\ImportExistingLoan;
use App\Models\LoanType;
use Carbon\Carbon;
use DB;
use Spatie\SimpleExcel\SimpleExcelReader;

class ImportLoans extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:import-loans';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        DB::beginTransaction();
        $source = SimpleExcelReader::create(storage_path('csv/deployment/LOANS- ALL AS OF DECEMBER 2024 - FINAL.xlsx'));
        $loanTypes = LoanType::get()->mapWithKeys(fn($l) => [$l->code => $l]);
        $members = Member::get()->mapWithKeys(fn($m) => [$m->mpc_code => $m]);
        $sheets = ['RL', 'CL', 'PL', 'EL', 'SL', 'KL', 'FNPL', 'ACL', 'LBP-RL', 'LBP-ATM'];
        foreach ($sheets as $key => $sheet) {
            $this->importLoanByType($source->fromSheetName($sheet)->getRows(), $members, $loanTypes[$sheet]);
        }
        DB::commit();
    }

    private function importLoanByType($rows, $members, $loanType)
    {
        $rows->each(function ($row) use ($members, $loanType) {
            try {
                if (filled($row['mpc_code'])) {
                    app(ImportExistingLoan::class)->handle(
                        member: $members[$row['mpc_code']],
                        loanType: $loanType,
                        reference_number: '#BALANCEFORWARDED',
                        amount: $row['desired_amount'],
                        balance_forwarded: $row['ending_balance'],
                        number_of_terms: $row['number_of_terms'],
                        application_date: Carbon::parse(str($row['date_applied'])->remove('.')->toString()),
                        last_transaction_date: Carbon::parse(str($row['last_transaction_date'])->remove('.')->toString()),
                    );
                }
            } catch (\Throwable $th) {
                dd($row, $th);
            }
        });
    }
}
