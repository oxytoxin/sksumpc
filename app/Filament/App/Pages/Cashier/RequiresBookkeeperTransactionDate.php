<?php

namespace App\Filament\App\Pages\Cashier;

use App\Livewire\BookkeeperTransactionDateChecker;

trait RequiresBookkeeperTransactionDate
{
    public $transaction_date;

    public function bootRequiresBookkeeperTransactionDate()
    {
        $this->transaction_date = config('app.transaction_date');
        $this->dispatch('refresh')->to(BookkeeperTransactionDateChecker::class);
        if (! $this->transaction_date) {
            return;
        }
    }
}
