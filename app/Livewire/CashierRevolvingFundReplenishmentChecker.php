<?php

namespace App\Livewire;

use App\Models\RevolvingFundReplenishment;
use Livewire\Component;

class CashierRevolvingFundReplenishmentChecker extends Component
{
    public $replenished = false;

    public function mount()
    {
        $this->replenished = RevolvingFundReplenishment::whereTransactionDate(config('app.transaction_date'))->whereCashierId(auth()->id())->exists();
    }

    public function render()
    {
        return view('livewire.cashier-revolving-fund-replenishment-checker');
    }
}
