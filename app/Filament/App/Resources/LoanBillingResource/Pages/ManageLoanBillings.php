<?php

namespace App\Filament\App\Resources\LoanBillingResource\Pages;

use App\Filament\App\Pages\Cashier\RequiresBookkeeperTransactionDate;
use App\Filament\App\Resources\LoanBillingResource;
use Filament\Actions;
use Filament\Resources\Pages\ManageRecords;
use Livewire\Attributes\Computed;

class ManageLoanBillings extends ManageRecords
{
    protected static string $resource = LoanBillingResource::class;

    use RequiresBookkeeperTransactionDate;

    #[Computed]
    public function UserIsCashier()
    {
        return auth()->user()->can('manage payments');
    }

    #[Computed]
    public function UserIsLoanOfficer()
    {
        return auth()->user()->can('manage loans');
    }

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make()
                ->createAnother(false),
        ];
    }
}
