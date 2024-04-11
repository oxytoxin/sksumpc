<?php

namespace App\Console\Commands;

use App\Actions\CapitalSubscription\CreateNewCapitalSubscription;
use App\Actions\CapitalSubscription\CreateNewCapitalSubscriptionAccount;
use App\Actions\CapitalSubscription\PayCapitalSubscription;
use DB;
use App\Models\User;
use App\Models\Gender;
use App\Models\Member;
use DateTimeImmutable;
use App\Models\Division;
use App\Models\Religion;
use Illuminate\Console\Command;
use Spatie\Permission\Models\Role;
use Spatie\SimpleExcel\SimpleExcelReader;
use App\Actions\Memberships\CreateMemberInitialAccounts;
use App\Models\CivilStatus;
use App\Models\TransactionType;
use App\Oxytoxin\DTO\CapitalSubscription\CapitalSubscriptionData;
use App\Oxytoxin\DTO\CapitalSubscription\CapitalSubscriptionPaymentData;
use App\Oxytoxin\DTO\MSO\Accounts\CapitalSubscriptionAccountData;

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
            $rows = SimpleExcelReader::create(storage_path('csv/PROFILING.xlsx'))
                ->getRows();
            $divisions = Division::get();
            $religions = Religion::get();
            $genders = Gender::get();
            $civil_statuses = CivilStatus::get();
            $transaction_type = TransactionType::firstWhere('name', 'CRJ');
            $rows->each(function (array $memberData) use ($divisions, $religions, $genders, $civil_statuses, $transaction_type) {
                try {
                    if ($memberData['mpc_code']) {
                        $memberData = collect($memberData)->map(fn ($d) => filled($d) ? trim($d instanceof DateTimeImmutable ? strtoupper($d->format('m/d/Y')) : strtoupper($d)) : null)->toArray();
                        $member = Member::create([
                            'mpc_code' => $memberData['mpc_code'],
                            'first_name' => $memberData['firstname'],
                            'last_name' => $memberData['lastname'],
                            'middle_name' => $memberData['mi'],
                            'middle_initial' => isset($memberData['mi']) ? $memberData['mi'][0] : null,
                            'member_type_id' => match ($memberData['member_type']) {
                                'REGULAR' => 1,
                                'ASSOCIATE' => 3,
                                'LABORATORY' => 4,
                                default => dd($memberData)
                            },
                            'division_id' => $divisions->firstWhere('name', $memberData['division'])?->id,
                            'religion_id' => $religions->firstWhere('name', $memberData['religion'])?->id,
                            'gender_id' => $genders->firstWhere('name', $memberData['gender'])?->id,
                            'civil_status_id' => $civil_statuses->firstWhere('name', $memberData['civil_status'])?->id,
                            'dob' => $memberData['dob'],
                            'tin' => $memberData['tin'],
                            'place_of_birth' => $memberData['birthplace'],
                            'present_employer' => $memberData['present_employer'],
                            'annual_income' => $memberData['annual_income'],
                            'highest_educational_attainment' => $memberData['highest_educational_attainment'],
                            'membership_date' => '12/30/2023',
                        ]);
                        $member->refresh();
                        app(CreateNewCapitalSubscriptionAccount::class)->handle(new CapitalSubscriptionAccountData(
                            member_id: $member->id,
                            name: $member->full_name
                        ));
                        $cbu = app(CreateNewCapitalSubscription::class)->handle($member, new CapitalSubscriptionData(
                            number_of_terms: 36,
                            number_of_shares: $memberData['no_shares'],
                            initial_amount_paid: $memberData['amount_paid'],
                            monthly_payment: ($memberData['amount_shares'] - $memberData['amount_paid']) / 36,
                            amount_subscribed: $memberData['amount_shares'],
                            par_value: $memberData['par_value'],
                            is_common: true,
                            transaction_date: '12/30/2023'
                        ));
                        app(PayCapitalSubscription::class)->handle($cbu, new CapitalSubscriptionPaymentData(
                            payment_type_id: 1,
                            reference_number: '#BALANCEFORWARDED',
                            amount: $memberData['amount_paid'],
                        ), $transaction_type);

                        $user = User::create([
                            'member_id' => $member->id,
                            'name' => $member->full_name,
                            'email' => str($member->mpc_code)->lower()->append('@gmail.com'),
                            'password' => 'password',
                        ]);
                        $user->assignRole(Role::firstWhere('name', 'member'));
                    }
                } catch (\Throwable $e) {
                    dd($memberData, $e);
                }
            });
        }
    }
}
