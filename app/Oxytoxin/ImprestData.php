<?php

namespace App\Oxytoxin;

class ImprestData
{
    public function __construct(public string $transaction_date, public string $type, public string $reference_number, public float $amount)
    {
    }
}
