<?php

namespace App\Filament\App\Pages;

use App\Models\CapitalSubscriptionPayment;
use App\Oxytoxin\ShareCapitalProvider;
use Filament\Pages\Page;
use Livewire\Attributes\Computed;

class ShareCapitalScheduleSummaryReport extends Page
{
    protected static ?string $navigationGroup = 'Share Capital';

    protected static ?string $navigationLabel = 'Schedule Summary';

    protected static ?int $navigationSort = 3;

    protected static string $view = 'filament.app.pages.share-capital-schedule-summary-report';

    public static function shouldRegisterNavigation(): bool
    {
        return auth()->user()->can('manage cbu');
    }

    private function getAmounts($amount_paid)
    {
        return [
            'shares_paid' => intdiv($amount_paid, ShareCapitalProvider::PAR_VALUE) * ShareCapitalProvider::PAR_VALUE,
            'shares_deposit' => $amount_paid - intdiv($amount_paid, ShareCapitalProvider::PAR_VALUE) * ShareCapitalProvider::PAR_VALUE,
            'amount_paid' => $amount_paid,
        ];
    }
    #[Computed]
    public function laboratory_amounts()
    {
        $amount_paid = CapitalSubscriptionPayment::whereHas('capital_subscription', function ($q) {
            return $q->whereRelation('member', 'member_type_id', 4);
        })->sum('amount');
        return $this->getAmounts($amount_paid);
    }
    #[Computed]
    public function regular_amounts()
    {
        $amount_paid =  CapitalSubscriptionPayment::whereHas('capital_subscription', function ($q) {
            return $q->whereHas('member', fn ($qu) => $qu->whereIn('member_type_id', [1, 2]));
        })->sum('amount');

        return $this->getAmounts($amount_paid);
    }
    #[Computed]
    public function associate_amounts()
    {
        $amount_paid = CapitalSubscriptionPayment::whereHas('capital_subscription', function ($q) {
            return $q->whereRelation('member', 'member_type_id', 3);
        })->sum('amount');
        return $this->getAmounts($amount_paid);
    }
}
