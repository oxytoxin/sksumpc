<?php

namespace App\Console\Commands;

use App\Models\Member;
use App\Models\MembershipStatus;
use App\Oxytoxin\ShareCapitalProvider;
use DateTimeImmutable;
use DB;
use Illuminate\Console\Command;
use Spatie\SimpleExcel\SimpleExcelReader;

class SeedMembers extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:seed-members';

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
            $rows = SimpleExcelReader::create(storage_path('csv/PROFILING.xlsx'))->getRows();
            $rows->each(function (array $memberData) {
                try {
                    $memberData = collect($memberData)->map(fn ($d) => filled($d) ? trim($d instanceof DateTimeImmutable ? strtoupper($d->format('m/d/Y')) : strtoupper($d)) : null)->toArray();
                    $membershipStatus = $memberData;
                    $memberData['civil_status_id'] = match ($memberData['civil_status']) {
                        'S' => 1,
                        'M' => 2,
                        'W' => 3,
                        default => null
                    };
                    $memberData['gender_id'] = match ($memberData['gender']) {
                        'M' => 1,
                        'F' => 2,
                        default => null
                    };
                    unset(
                        $memberData['member_type'],
                        $memberData['membership_number'],
                        $memberData['occupation'],
                        $memberData['religion'],
                        $memberData['civil_status'],
                        $memberData['gender'],
                        $memberData['acceptance_bod_resolution'],
                        $memberData['accepted_at'],
                        $memberData['termination_bod_resolution'],
                        $memberData['terminated_at'],
                        $memberData['number_of_shares'],
                        $memberData['amount_subscribed'],
                        $memberData['initial_amount_paid'],
                        $memberData['no_dependents'],
                    );

                    $memberData['dependents'] = [];
                    $memberData['other_income_sources'] = null;

                    if ($memberData['member_type_id'] == 1) {
                        $memberData['present_employer'] = 'SKSU-Sultan Kudarat State University';
                    }
                    $member = Member::create($memberData);
                    if ($membershipStatus['accepted_at']) {
                        $member->membership_acceptance()->create([
                            'type' => MembershipStatus::ACCEPTANCE,
                            'bod_resolution' => $membershipStatus['acceptance_bod_resolution'],
                            'effectivity_date' => $membershipStatus['accepted_at'],
                        ]);
                    }
                    if ($membershipStatus['terminated_at']) {
                        $member->membership_termination()->create([
                            'type' => MembershipStatus::TERMINATION,
                            'bod_resolution' => $membershipStatus['termination_bod_resolution'],
                            'effectivity_date' => $membershipStatus['terminated_at'],
                        ]);
                    }
                } catch (\Throwable $e) {
                    dd($membershipStatus, $e->getMessage());
                }
            });
            DB::commit();
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
        $member = Member::where('mpc_code', $data['mpc_code'])->first();
        if ($member) {
            try {
                $cbu = $member->initial_capital_subscription()->create([
                    'code' => ShareCapitalProvider::EXISTING_CAPITAL_CODE,
                    'number_of_shares' => $data['shares_subscribed'],
                    'number_of_terms' => ShareCapitalProvider::ADDITIONAL_NUMBER_OF_TERMS,
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
                    'cashier_id' => 1
                ]);
            } catch (\Throwable $e) {
                dd($data, $e->getMessage());
            }
        }
    }
}
