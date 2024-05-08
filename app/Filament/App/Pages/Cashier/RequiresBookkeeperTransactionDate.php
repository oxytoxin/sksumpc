<?php

namespace App\Filament\App\Pages\Cashier;

use App\Models\SystemConfiguration;

trait RequiresBookkeeperTransactionDate
{
    public $transaction_date;

    public function boot()
    {
        $this->transaction_date = SystemConfiguration::config('Transaction Date')?->content['transaction_date'];
        if (!$this->transaction_date) {
            return;
        }
    }
}
