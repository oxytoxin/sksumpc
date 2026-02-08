<?php

namespace App\Console\Commands;

use App\Actions\TimeDeposits\RolloverMaturedTimeDepositsAction;
use Illuminate\Console\Command;

class RolloverMaturedTimeDeposits extends Command
{
    protected $signature = 'time-deposits:rollover-matured {--date= : The date to check for matured deposits (defaults to today)}';

    protected $description = 'Manually trigger automatic time deposit rollover for matured deposits';

    public function handle()
    {
        $date = $this->option('date');

        $this->info('Starting automatic time deposit rollover process...');

        $result = app(RolloverMaturedTimeDepositsAction::class)->handle($date);

        if ($result['successful'] === 0 && $result['failed'] === 0) {
            $this->info('No matured time deposits found to rollover.');

            return Command::SUCCESS;
        }

        $this->info("Rollover process completed. Successful: {$result['successful']}, Failed: {$result['failed']}");

        return Command::SUCCESS;
    }
}
