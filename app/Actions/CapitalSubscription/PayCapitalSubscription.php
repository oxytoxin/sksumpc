<?php

namespace App\Actions\CapitalSubscription;

use App\Models\CapitalSubscription;
use App\Oxytoxin\DTO\CapitalSubscription\CapitalSubscriptionPaymentData;
use Lorisleiva\Actions\Concerns\AsAction;

class PayCapitalSubscription
{
    use AsAction;

    public function handle(CapitalSubscription $cbu, CapitalSubscriptionPaymentData $data)
    {
        $cbu->payments()->create($data->toArray());
    }
}
