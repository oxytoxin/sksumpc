<?php

namespace App\Filament\App\Resources\MemberResource\Pages;

use App\Filament\App\Resources\MemberResource;
use App\Models\TimeDepositAccount;
use Filament\Resources\Pages\Page;
use Livewire\Attributes\Computed;

class TimeDepositSubsidiaryLedger extends Page
{
    protected static string $resource = MemberResource::class;

    protected static string $view = 'filament.app.resources.member-resource.pages.time-deposit-subsidiary-ledger';

    public TimeDepositAccount $time_deposit_account;

    #[Computed]
    public function TimeDeposit()
    {
        return $this->time_deposit_account->time_deposit;
    }
}
