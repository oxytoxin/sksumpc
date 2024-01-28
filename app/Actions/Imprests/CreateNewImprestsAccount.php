<?php

namespace App\Actions\Imprests;

use App\Models\ImprestAccount;
use App\Oxytoxin\DTO\MSO\Accounts\ImprestAccountData;
use Lorisleiva\Actions\Concerns\AsAction;

class CreateNewImprestsAccount
{
    use AsAction;

    public function handle(ImprestAccountData $imprestAccountData)
    {
        ImprestAccount::create($imprestAccountData->toArray());
    }
}
