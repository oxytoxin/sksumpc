<?php

namespace App\Console\Commands;

use App\Models\Organization;
use Illuminate\Console\Command;
use Spatie\SimpleExcel\SimpleExcelReader;
use App\Actions\Memberships\CreateMemberInitialAccounts;
use App\Models\Member;

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
        $members = Member::get()->mapWithKeys(fn($m) => [$m->mpc_code => $m->id]);
        $rows->each(function ($row) use ($members) {
            $organization = Organization::create([
                'mpc_code' => trim(strtoupper($row['mpc_code'])),
                'member_type_id' => 4,
                'first_name' => trim(strtoupper($row['name'])),
                'is_organization' => true,
                'member_ids' => collect(explode(',', $row['member_ids']))->filter()->map(fn($mi) => $members[$mi])->toArray(),
            ]);
            app(CreateMemberInitialAccounts::class)->handle($organization);
        });
    }
}
