<?php

namespace App\Oxytoxin;

class SavingsData
{
    public function __construct(public string $transaction_date, public string $reference_number, public float $amount)
    {
    }
}
