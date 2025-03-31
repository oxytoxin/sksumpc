<?php

namespace App\Console\Commands;

use App\Actions\LoveGifts\GenerateLoveGiftsInterestForMember;
use App\Models\Member;
use Illuminate\Console\Command;

class DepositQuarterlyInterestForLoveGifts extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:generate-interest-for-love-gifts';

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
            app(GenerateLoveGiftsInterestForMember::class)->handle($member);
        });
    }
}
