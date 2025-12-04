<?php

namespace App\Console\Commands;

use App\Actions\Memberships\CreateMemberInitialAccounts;
use App\Models\CivilStatus;
use App\Models\Division;
use App\Models\Gender;
use App\Models\Member;
use App\Models\Religion;
use App\Models\User;
use DateTimeImmutable;
use DB;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Schema;
use Spatie\Permission\Models\Role;
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
        Schema::disableForeignKeyConstraints();
        DB::table('members')->truncate();
        $rows = SimpleExcelReader::create(storage_path('csv/deployment/MEMBERS PROFILING AS OF DECEMBER 2024 - FINAL.xlsx'))
            ->getRows();
        $divisions = Division::get()->mapWithKeys(fn ($g) => [$g->name => $g->id]);
        $religions = Religion::get()->mapWithKeys(fn ($g) => [$g->name => $g->id]);
        $genders = Gender::get()->mapWithKeys(fn ($g) => [$g->name => $g->id]);
        $civil_statuses = CivilStatus::get()->mapWithKeys(fn ($g) => [$g->name => $g->id]);
        $role = Role::firstWhere('name', 'member');
        $rows->each(function (array $row) use ($divisions, $religions, $genders, $civil_statuses, $role) {
            try {
                if ($row['mpc_code']) {
                    $address = [];
                    $address_parts = [
                        $row['barangay'],
                        $row['municipality'],
                        $row['province'],
                        $row['region'],
                    ];
                    foreach ($address_parts as $part) {
                        if (filled($part)) {
                            $address[] = $part;
                        }
                    }
                    $member_address = implode(', ', $address);
                    $row = collect($row)->map(fn ($d) => filled($d) ? trim($d instanceof DateTimeImmutable ? strtoupper($d->format('m/d/Y')) : strtoupper($d)) : null)->toArray();
                    $member = Member::create([
                        'mpc_code' => trim(strtoupper($row['mpc_code'])),
                        'first_name' => trim(strtoupper($row['fname'])),
                        'last_name' => trim(strtoupper($row['lname'])),
                        'middle_name' => trim(strtoupper($row['mname'])),
                        'address' => trim(strtoupper($member_address)),
                        'member_type_id' => match ($row['member_type']) {
                            'REGULAR' => 1,
                            'NOT CONNECTED' => 1,
                            'ASSOCIATE' => 2,
                            'LABORATORY' => 3,
                            default => dd($row)
                        },
                        'member_subtype_id' => match ($row['member_type']) {
                            'REGULAR' => 1,
                            'NOT CONNECTED' => 3,
                            default => null
                        },
                        'occupation_description' => trim(strtoupper($row['occupation'])),
                        'division_id' => $divisions[$row['division']] ?? null,
                        'religion_id' => $religions[$row['religion']] ?? null,
                        'gender_id' => $genders[$row['gender']] ?? null,
                        'civil_status_id' => $civil_statuses[$row['civil_status']] ?? null,
                        'dob' => $row['dob'],
                        'tin' => trim($row['tin']),
                        'place_of_birth' => filled($row['place_of_birth']) ? trim(strtoupper($row['place_of_birth'])) : null,
                        'present_employer' => filled($row['present_employer']) ? trim(strtoupper($row['present_employer'])) : null,
                        'annual_income' => filled($row['annual_income']) ? trim($row['annual_income']) : null,
                        'highest_educational_attainment' => trim(strtoupper($row['highest_educational_attainment'])),
                        'membership_date' => $row['effectivity_date'] ?? '12/31/2024',
                    ]);
                    $member->refresh();
                    $user = User::create([
                        'member_id' => $member->id,
                        'name' => $member->full_name ?? ($member->first_name.' '.$member->last_name),
                        'email' => str($member->mpc_code)->lower()->append('@gmail.com'),
                        'password' => 'password',
                    ]);
                    $user->assignRole($role);
                    // app(CreateMemberInitialAccounts::class)->handle($member);
                }
            } catch (\Throwable $e) {
                dump($row['mpc_code'], $e->getMessage());
            }
        });

        // DB::statement('ALTER TABLE members AUTO_INCREMENT =  1');
        // DB::unprepared(file_get_contents(database_path('migrations/members.sql')));
        Schema::enableForeignKeyConstraints();
    }
}
