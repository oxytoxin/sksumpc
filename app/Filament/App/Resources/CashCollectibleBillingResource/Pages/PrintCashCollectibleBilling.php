<?php

namespace App\Filament\App\Resources\CashCollectibleBillingResource\Pages;

use App\Filament\App\Resources\CashCollectibleBillingResource;
use App\Models\CashCollectibleBilling;
use Filament\Resources\Pages\Page;

class PrintCashCollectibleBilling extends Page
{
    protected static string $resource = CashCollectibleBillingResource::class;

    protected string $view = 'filament.app.resources.cash-collectible-billing-resource.pages.print-cash-collectible-billing';

    public CashCollectibleBilling $cash_collectible_billing;
}
