<?php

namespace App\Models;

use App\Oxytoxin\Providers\ShareCapitalProvider;
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
        'is_active' => 'boolean',
        'number_of_shares' => 'decimal:4',
        'par_value' => 'decimal:4',
        'amount_subscribed' => 'decimal:4',
        'initial_amount_paid' => 'decimal:4',
        'number_of_terms' => 'integer',
        'transaction_date' => 'immutable_date',
    ];

    public function member(): BelongsTo
    {
        return $this->belongsTo(Member::class);
    }

    public function payments()
    {
        return $this->hasMany(CapitalSubscriptionPayment::class);
    }

    public function capital_subscription_amortizations()
    {
        return $this->hasMany(CapitalSubscriptionAmortization::class);
    }

    public function paid_capital_subscription_amortizations()
    {
        return $this->hasMany(CapitalSubscriptionAmortization::class)->whereNotNull('amount_paid');
    }

    public function active_capital_subscription_amortization()
    {
        return $this->hasOne(CapitalSubscriptionAmortization::class)->whereNull('amount_paid')->orWhere('arrears', '>', 0);
    }

    protected static function booted(): void
    {
        static::creating(function (CapitalSubscription $cbu) {
            $cbu->outstanding_balance = $cbu->amount_subscribed;
            $cbu->initial_amount_paid ??= $cbu->member->member_type->minimum_initial_payment;
        });

        static::created(function (CapitalSubscription $cbu) {
            if ($cbu->member->capital_subscriptions()->count() == 1) {
                $code = str('ICS-')->append((config('app.transaction_date') ?? today())->format('Y'))->append('-')->append(str_pad($cbu->id, 6, '0', STR_PAD_LEFT));
            } else {
                $code = str('ACS-')->append((config('app.transaction_date') ?? today())->format('Y'))->append('-')->append(str_pad($cbu->id, 6, '0', STR_PAD_LEFT));
            }
            $cbu->code = $code;
            $cbu->save();
        });
    }

    public function amountSharesSubscribed(): Attribute
    {
        return Attribute::make(
            get: fn() => $this->number_of_shares * $this->par_value
        );
    }
}
