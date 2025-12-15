<?php

namespace App\Filament\App\Resources\CapitalSubscriptionResource\Pages;

use App\Filament\App\Resources\CapitalSubscriptionResource;
use App\Models\CapitalSubscription;
use Filament\Resources\Pages\Page;
use Livewire\Attributes\Computed;
use NumberFormatter;

class ShareCertificate extends Page
{
    protected static string $resource = CapitalSubscriptionResource::class;

    protected string $view = 'filament.app.resources.capital-subscription-resource.pages.share-certificate';

    public CapitalSubscription $capital_subscription;

    #[Computed]
    public function Member()
    {
        return $this->capital_subscription->member;
    }

    #[Computed]
    public function ParValue()
    {
        $formatter = NumberFormatter::create('en', NumberFormatter::SPELLOUT);

        return strtoupper($formatter->format($this->capital_subscription->par_value)).' PESOS';
    }
}
