<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * @mixin IdeHelperCapitalSubscriptionAmortization
 */
class CapitalSubscriptionAmortization extends Model
{
    use HasFactory;

    protected $casts = [
        'amount' => 'decimal:4',
        'term' => 'integer',
        'due_date' => 'immutable_date'
    ];

    public function capital_subscription()
    {
        return $this->belongsTo(CapitalSubscription::class);
    }
}
