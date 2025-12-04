<?php

namespace App\Actions\RevolvingFund;

use App\Models\RevolvingFund;

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
