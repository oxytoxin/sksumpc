<?php

namespace App\Console\Commands;

use App\Actions\Savings\GenerateSavingsInterestForMember;
use App\Models\Member;
use DB;
use Illuminate\Console\Command;

class DepositQuarterlyInterestForSavings extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:generate-interest-for-savings';

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
        Member::each(function ($member) {
            app(GenerateSavingsInterestForMember::class)->handle($member);
        });
    }
}
