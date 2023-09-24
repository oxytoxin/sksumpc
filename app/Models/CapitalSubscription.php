<?php

namespace App\Models;

use App\Oxytoxin\ShareCapitalProvider;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class CapitalSubscription extends Model
{
    use HasFactory;

    protected $casts = [
        'number_of_shares' => 'integer',
        'amount_subscribed' => 'decimal:2',
        'initial_amount_paid' => 'decimal:2',
        'number_of_terms' => 'integer'
    ];

    public function member(): BelongsTo
    {
        return $this->belongsTo(Member::class);
    }

    public function payments()
    {
        return $this->hasMany(CapitalSubscriptionPayment::class);
    }

    protected static function booted(): void
    {
        static::creating(function (CapitalSubscription $cbu) {
            $cbu->outstanding_balance = $cbu->number_of_shares * ShareCapitalProvider::PAR_VALUE;
        });
    }

    public function amountSharesSubscribed(): Attribute
    {
        return Attribute::make(
            get: fn () => $this->number_of_shares * ShareCapitalProvider::PAR_VALUE
        );
    }
}
