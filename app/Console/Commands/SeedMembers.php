<?php

namespace App\Console\Commands;

use App\Models\Member;
use App\Models\MembershipStatus;
use App\Oxytoxin\ShareCapitalProvider;
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
        DB::beginTransaction();
        if (!Member::count()) {
            $rows = SimpleExcelReader::create(storage_path('csv/mpc_members.csv'))->getRows();
            $rows->each(function (array $memberData) {
                $memberData = collect($memberData)->map(fn ($d) => filled($d) ? trim($d) : null)->toArray();
                $membershipStatus = $memberData;
                unset(
                    $memberData['member_type'],
                    $memberData['membership_number'],
                    $memberData['occupation'],
                    $memberData['religion'],
                    $memberData['acceptance_bod_resolution'],
                    $memberData['accepted_at'],
                    $memberData['termination_bod_resolution'],
                    $memberData['terminated_at'],
                    $memberData['number_of_shares'],
                    $memberData['amount_subscribed'],
                    $memberData['initial_amount_paid'],
                );

                $memberData['dependents'] = [];
                $memberData['other_income_sources'] = [];
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

                if (
                    $membershipStatus['number_of_shares'] &&
                    $membershipStatus['amount_subscribed'] &&
                    $membershipStatus['initial_amount_paid']
                ) {
                    $cbu = $member->initial_capital_subscription()->create([
                        'code' => ShareCapitalProvider::INITIAL_CAPITAL_CODE,
                        'number_of_shares' => $membershipStatus['number_of_shares'],
                        'number_of_terms' => 12,
                        'amount_subscribed' => $membershipStatus['amount_subscribed'],
                        'initial_amount_paid' => $membershipStatus['initial_amount_paid'],
                    ]);

                    $cbu->payments()->create([
                        'amount' => $membershipStatus['initial_amount_paid'],
                        'reference_number' => '#INITIALAMOUNTPAID',
                        'type' => 'OR',
                    ]);
                }
            });
        }
        DB::commit();
    }
}
