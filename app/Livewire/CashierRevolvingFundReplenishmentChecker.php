<?php

namespace App\Livewire;

use App\Models\RevolvingFund;
use Auth;
use Livewire\Component;

class CashierRevolvingFundReplenishmentChecker extends Component
{
    public $replenished = false;

    public function mount()
    {
        $balance = RevolvingFund::query()
            ->whereCashierId(auth()->id())
            ->whereMonth('transaction_date', config('app.transaction_date')?->month)
            ->whereYear('transaction_date', config('app.transaction_date')?->year)
            ->selectRaw('(coalesce(sum(deposit), 0) - coalesce(sum(withdrawal), 0)) as balance')
            ->first()?->balance;
        $this->replenished = ($balance && $balance > 0) || !Auth::user()->can('manage payments') || Auth::user()->can('manage bookkeeping');
    }

    public function render()
    {
        return view('livewire.cashier-revolving-fund-replenishment-checker');
    }
}
