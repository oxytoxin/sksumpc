<?php

namespace App\Console\Commands;

use App\Models\User;
use App\Models\Member;
use App\Models\Organization;
use Illuminate\Console\Command;
use Spatie\Permission\Models\Role;
use Spatie\SimpleExcel\SimpleExcelReader;
use App\Actions\Memberships\CreateMemberInitialAccounts;

class ImportOrganizations extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:import-organizations';

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
        $rows = SimpleExcelReader::create(storage_path('csv/deployment/MEMBERS PROFILING AS OF DECEMBER 2024 - FINAL.xlsx'))
            ->fromSheetName('ORG LIST')
            ->getRows();
        $role = Role::firstWhere('name', 'member');
        $members = Member::get()->mapWithKeys(fn($m) => [$m->mpc_code => $m->id]);
        $rows->each(function ($row) use ($members, $role) {
            if (filled($row['mpc_code'])) {
                $organization = Organization::create([
                    'mpc_code' => trim(strtoupper($row['mpc_code'])),
                    'member_type_id' => 4,
                    'first_name' => trim(strtoupper($row['name'])),
                    'is_organization' => true,
                    'member_ids' => collect(explode(',', $row['member_ids']))->filter()->map(fn($mi) => $members[$mi])->toArray(),
                ]);
                $organization->refresh();
                $user = User::create([
                    'member_id' => $organization->id,
                    'name' => $organization->full_name ?? ($organization->first_name . ' ' . $organization->last_name),
                    'email' => str($organization->mpc_code)->lower()->append('@gmail.com'),
                    'password' => 'password',
                ]);
                $user->assignRole($role);
            }
        });
    }
}
