<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class CapitalSubscription extends Model
{
    use HasFactory;

    protected $casts = [
        'number_of_shares' => 'decimal:2',
        'amount_subscribed' => 'decimal:2',
        'initial_amount_paid' => 'decimal:2',
        'is_ics' => 'boolean'
    ];

    public function member(): BelongsTo
    {
        return $this->belongsTo(Member::class);
    }
}
