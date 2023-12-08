<?php

namespace App\Filament\App\Resources\CapitalSubscriptionResource\Pages\Reports;

use App\Filament\App\Resources\CapitalSubscriptionResource;
use Filament\Resources\Pages\Page;

class TopTenHighestCbuReport extends Page
{
    protected static string $resource = CapitalSubscriptionResource::class;

    protected static string $view = 'filament.app.resources.capital-subscription-resource.pages.reports.top-ten-highest-cbu-report';
}
