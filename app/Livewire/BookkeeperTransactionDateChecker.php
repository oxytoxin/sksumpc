<?php

namespace App\Livewire;

use Livewire\Attributes\On;
use Livewire\Component;

class BookkeeperTransactionDateChecker extends Component
{
    public $transaction_date;

    #[On('refresh')]
    public function refresh()
    {
        $this->transaction_date = config('app.transaction_date');
    }

    public function mount()
    {
        $this->transaction_date = config('app.transaction_date');
    }

    public function render()
    {
        return view('livewire.bookkeeper-transaction-date-checker');
    }
}
