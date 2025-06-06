<?php

namespace App\Livewire;

use App\Models\Account;
use App\Models\TransactionType;
use App\Oxytoxin\Providers\TrialBalanceProvider;
use Carbon\CarbonImmutable;
use Livewire\Attributes\Computed;
use Livewire\Component;

class Testcomponent extends Component
{
    #[Computed]
    public function TransactionTypes()
    {
        return TransactionType::get();
    }

    #[Computed]
    public function Accounts()
    {
        return Account::tree()
            ->orderBy('sort')
            ->whereNull('member_id')
            ->get()
            ->toTree();
    }

    #[Computed]
    public function FormattedBalanceForwardedDate()
    {
        return CarbonImmutable::create(year: 2024)->subYearNoOverflow()->endOfYear()->format('F Y');
    }

    #[Computed]
    public function TrialBalance()
    {
        return TrialBalanceProvider::getSingleTrialBalance($this->selected_month);
    }

    #[Computed]
    public function SelectedMonth()
    {
        return CarbonImmutable::create(2023, 12, 31);
    }

    public function render()
    {
        return view('livewire.testcomponent');
    }
}
