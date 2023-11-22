<?php

namespace App\Filament\App\Resources\MemberResource\Pages;

use App\Filament\App\Pages\Cashier\Reports\HasSignatories;
use App\Filament\App\Resources\MemberResource;
use App\Models\CapitalSubscription;
use Filament\Resources\Pages\Page;

class CbuAmortizationSchedule extends Page
{
    use HasSignatories;

    public CapitalSubscription $cbu;

    protected static string $resource = MemberResource::class;

    protected static string $view = 'filament.app.resources.member-resource.pages.cbu-amortization-schedule';

    public function mount()
    {
        $this->cbu->load('capital_subscription_amortizations');
        $this->getSignatories();
    }
}
