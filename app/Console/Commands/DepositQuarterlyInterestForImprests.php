<?php

namespace App\Console\Commands;

use App\Actions\Imprests\GenerateImprestsInterestForMember;
use App\Models\Member;
use Illuminate\Console\Command;

class DepositQuarterlyInterestForImprests extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'a app:generate-interest-for-imprests';

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
            app(GenerateImprestsInterestForMember::class)->handle($member);
        });
    }
}
