<?php

namespace App\Filament\App\Resources\CapitalSubscriptionBillingResource\Pages;

use App\Filament\App\Resources\CapitalSubscriptionBillingResource;
use App\Models\CapitalSubscriptionBilling;
use Filament\Resources\Pages\Page;

class PrintCapitalSubscriptionBilling extends Page
{
    protected static string $resource = CapitalSubscriptionBillingResource::class;

    protected string $view = 'filament.app.resources.capital-subscription-billing-resource.pages.print-capital-subscription-billing';

    public CapitalSubscriptionBilling $capital_subscription_billing;
}
