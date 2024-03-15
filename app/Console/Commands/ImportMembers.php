<?php

namespace App\Console\Commands;

use App\Actions\Memberships\CreateMemberInitialAccounts;
use App\Models\Member;
use App\Models\MembershipStatus;
use DateTimeImmutable;
use DB;
use Illuminate\Console\Command;
use Spatie\SimpleExcel\SimpleExcelReader;

class ImportMembers extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:import-members';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Seeds members from csv. Only works when there are no members.';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        if (!Member::count()) {
            DB::beginTransaction();
            $rows = SimpleExcelReader::create(storage_path('csv/MEMBERSDATA.xlsx'))
                ->headerOnRow(1)
                ->getRows();
            $rows->each(function (array $memberData) {
                try {
                    $memberData = collect($memberData)->map(fn ($d) => filled($d) ? trim($d instanceof DateTimeImmutable ? strtoupper($d->format('m/d/Y')) : strtoupper($d)) : null)->toArray();
                    Member::create([
                        'mpc_code' => $memberData['SKSU MPC NUMBER'],
                        'first_name' => $memberData['Firstname'],
                        'last_name' => $memberData['Lastname'],
                        'middle_name' => $memberData['MI'],
                        'middle_initial' => isset($memberData['MI']) ? $memberData['MI'][0] : null,
                        'member_type_id' => match ($memberData['Members Type']) {
                            'REGULAR' => 1,
                            'ASSOCIATE' => 3,
                            'LABORATORY' => 4,
                            default => dd($memberData)
                        }
                    ]);
                } catch (\Throwable $e) {
                    dd($memberData, $e->getMessage());
                }
            });
            DB::commit();
            foreach (Member::all() as $key => $member) {
                app(CreateMemberInitialAccounts::class)->handle($member);
            }
        }
        // DB::beginTransaction();
        // $rows = SimpleExcelReader::create(storage_path('csv/CBU.xlsx'))->fromSheetName('REGULAR')->getRows();
        // $rows->each(function ($data) {
        //     $this->seedCBU($data);
        // });
        // $rows = SimpleExcelReader::create(storage_path('csv/CBU.xlsx'))->fromSheetName('ASSOCIATE')->getRows();
        // $rows->each(function ($data) {
        //     $this->seedCBU($data);
        // });
        // $rows = SimpleExcelReader::create(storage_path('csv/CBU.xlsx'))->fromSheetName('LABORATORY')->getRows();
        // $rows->each(function ($data) {
        //     $this->seedCBU($data);
        // });
        // DB::commit();
    }

    private function seedCBU($data)
    {
        $member = Member::where('mpc_code', $data['mpc_code'])->with('member_type')->first();
        if ($member) {
            try {
                $cbu = $member->initial_capital_subscription()->create([
                    'code' => '',
                    'number_of_shares' => $data['shares_subscribed'],
                    'number_of_terms' => $member->member_type->additional_number_of_terms,
                    'is_common' => true,
                    'par_value' => $data['amount_shares_subscribed'] / $data['shares_subscribed'],
                    'amount_subscribed' => $data['amount_shares_subscribed'],
                    'transaction_date' => today()->subYear()->endOfYear(),
                ]);
                $payment = $cbu->payments()->create([
                    'amount' => $data['amount_shares_paid_total'],
                    'reference_number' => '#BALANCEFORWARDED',
                    'payment_type_id' => 1,
                    'transaction_date' => today()->subYear()->endOfYear(),
                ]);
                $payment->update([
                    'cashier_id' => 1,
                ]);
            } catch (\Throwable $e) {
                dd($data, $e->getMessage());
            }
        }
    }
}
