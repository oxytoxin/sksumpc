<?php

namespace App\Models;

use App\Oxytoxin\ShareCapitalProvider;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * @mixin IdeHelperCapitalSubscription
 */
class CapitalSubscription extends Model
{
    use HasFactory;

    protected $casts = [
        'is_common' => 'boolean',
        'number_of_shares' => 'decimal:2',
        'par_value' => 'decimal:2',
        'amount_subscribed' => 'decimal:2',
        'number_of_terms' => 'integer',
        'transaction_date' => 'immutable_date'
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
            $cbu->outstanding_balance = $cbu->amount_subscribed;
        });

        static::created(function (CapitalSubscription $cbu) {
            if ($cbu->member->capital_subscriptions()->count() == 1) {
                $code = 'Initial Capital Subscription';
            } else {
                $code = str('ACS-')->append(today()->format('Y'))->append('-')->append(str_pad($cbu->id, 6, '0', STR_PAD_LEFT));
            }
            $cbu->code = $code;
            $cbu->save();
        });
    }

    public function amountSharesSubscribed(): Attribute
    {
        return Attribute::make(
            get: fn () => $this->number_of_shares * $this->par_value
        );
    }
}
