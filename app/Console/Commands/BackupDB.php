<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

class BackupDB extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'backup:db';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Create Database Snapshot';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->call('snapshot:create');
        $this->call('snapshot:cleanup', ['--keep' => 5]);
    }
}
