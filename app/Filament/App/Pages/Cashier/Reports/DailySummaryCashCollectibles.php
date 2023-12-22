<?php

namespace App\Filament\App\Pages\Cashier\Reports;

use Filament\Pages\Page;
use Livewire\Attributes\Computed;

class DailySummaryCashCollectibles extends Page
{
    use HasSignatories;

    protected static bool $shouldRegisterNavigation = false;

    protected static string $view = 'filament.app.pages.cashier.reports.daily-summary-cash-collectibles';

    #[Computed]
    public function cashCollectibles()
    {
        return auth()
            ->user()
            ->cashier_cash_collectible_payments()
            ->whereDate('transaction_date', today())
            ->join('payment_types', 'cash_collectible_payments.payment_type_id', '=', 'payment_types.id')
            ->join('cash_collectibles', 'cash_collectible_payments.cash_collectible_id', '=', 'cash_collectibles.id')
            ->groupBy(['payment_type_id', 'cash_collectible_id', 'payment_types.name', 'cash_collectibles.name'])
            ->selectRaw('payment_type_id, cash_collectible_id, cash_collectibles.name as cash_collectible, sum(amount) as total_amount, payment_types.name as payment_type')
            ->get()
            ->groupBy('payment_type');
    }
}
