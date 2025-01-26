<?php

namespace App\Actions\RevolvingFund;

use App\Actions\Transactions\CreateTransaction;
use App\Models\Account;
use App\Models\RevolvingFund;
use App\Models\TransactionType;
use App\Oxytoxin\DTO\Transactions\TransactionData;

class ReplenishRevolvingFund
{
    public function handle($reference_number, $amount)
    {
        $rf = RevolvingFund::create([
            'reference_number' => $reference_number,
            'deposit' => $amount,
            'transaction_date' => config('app.transaction_date'),
        ]);
        return $rf;
    }
}
