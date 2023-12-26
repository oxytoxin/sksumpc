<?php

namespace App\Filament\App\Pages\Cashier\Reports;

use App\Models\CashCollectiblePayment;
use Filament\Pages\Page;
use Livewire\Attributes\Computed;

class DailyCollectionReportLadiesDormitory extends Page
{
    use HasSignatories;

    protected static bool $shouldRegisterNavigation = false;

    protected static string $view = 'filament.app.pages.cashier.reports.daily-collection-report-ladies-dormitory';

    protected ?string $heading = 'Daily Collection Report of Ladies Dormitory';

    #[Computed]
    public function payments()
    {
        return CashCollectiblePayment::whereRelation('cash_collectible', 'cash_collectible_category_id', 2)->whereDate('transaction_date', today())->get();
    }
}
