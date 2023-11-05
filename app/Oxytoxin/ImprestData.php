<?php

namespace App\Oxytoxin;

class ImprestData
{
    public function __construct(public string $transaction_date, public int $payment_type_id, public string $reference_number, public float $amount)
    {
    }
}
