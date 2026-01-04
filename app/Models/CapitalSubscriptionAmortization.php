<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * @property-read \App\Models\CapitalSubscription|null $capital_subscription
 * @method static \Illuminate\Database\Eloquent\Builder<static>|CapitalSubscriptionAmortization newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|CapitalSubscriptionAmortization newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|CapitalSubscriptionAmortization query()
 * @mixin \Eloquent
 */
class CapitalSubscriptionAmortization extends Model
{
    use HasFactory;

    protected $casts = [
        'amount' => 'decimal:4',
        'term' => 'integer',
        'due_date' => 'immutable_date',
    ];

    public function capital_subscription()
    {
        return $this->belongsTo(CapitalSubscription::class);
    }
}
