<?php

namespace App\Filament\App\Resources\CapitalSubscriptionBillingResource\Pages;

use App\Filament\App\Resources\CapitalSubscriptionBillingResource;
use Filament\Actions;
use Filament\Resources\Pages\ManageRecords;
use Livewire\Attributes\Computed;

class ManageCapitalSubscriptionBillings extends ManageRecords
{
    protected static string $resource = CapitalSubscriptionBillingResource::class;

    #[Computed]
    public function UserIsCashier()
    {
        return auth()->user()->can('manage payments');
    }

    #[Computed]
    public function UserIsCbuOfficer()
    {
        return auth()->user()->can('manage cbu');
    }

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make()
                ->createAnother(false),
        ];
    }
}
