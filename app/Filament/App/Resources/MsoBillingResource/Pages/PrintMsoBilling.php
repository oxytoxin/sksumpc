<?php

namespace App\Filament\App\Resources\MsoBillingResource\Pages;

use App\Filament\App\Resources\MsoBillingResource;
use App\Models\MsoBilling;
use Filament\Resources\Pages\Page;

class PrintMsoBilling extends Page
{
    protected static string $resource = MsoBillingResource::class;

    protected string $view = 'filament.app.resources.mso-billing-resource.pages.print-mso-billing';

    public MsoBilling $mso_billing;
}
