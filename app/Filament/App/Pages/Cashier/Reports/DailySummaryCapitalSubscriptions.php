<?php

namespace App\Filament\App\Pages\Cashier\Reports;

use Filament\Pages\Page;
use Livewire\Attributes\Computed;

class DailySummaryCapitalSubscriptions extends Page
{
    use HasSignatories;

    protected static bool $shouldRegisterNavigation = false;

    protected static string $view = 'filament.app.pages.cashier.reports.daily-summary-capital-subscriptions';

    #[Computed]
    public function cbu_payments()
    {
        return auth()
            ->user()
            ->cashier_cbu_payments()
            ->with('capital_subscription.member')
            ->whereDate('transaction_date', today())
            ->get();
    }
}
