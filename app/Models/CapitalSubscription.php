<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * @property int $id
 * @property int $member_id
 * @property bool $is_active
 * @property string|null $code
 * @property int $number_of_terms
 * @property numeric $number_of_shares
 * @property numeric $amount_subscribed
 * @property numeric|null $monthly_payment
 * @property numeric|null $initial_amount_paid
 * @property numeric $par_value
 * @property numeric $actual_amount_paid
 * @property numeric|null $total_amount_paid
 * @property numeric $outstanding_balance
 * @property \Carbon\CarbonImmutable $transaction_date
 * @property \Carbon\CarbonImmutable|null $created_at
 * @property \Carbon\CarbonImmutable|null $updated_at
 * @property-read \App\Models\CapitalSubscriptionAmortization|null $active_capital_subscription_amortization
 * @property-read mixed $amount_shares_subscribed
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\CapitalSubscriptionAmortization> $capital_subscription_amortizations
 * @property-read int|null $capital_subscription_amortizations_count
 * @property-read \App\Models\Member $member
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\CapitalSubscriptionAmortization> $paid_capital_subscription_amortizations
 * @property-read int|null $paid_capital_subscription_amortizations_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\CapitalSubscriptionPayment> $payments
 * @property-read int|null $payments_count
 * @method static \Illuminate\Database\Eloquent\Builder<static>|CapitalSubscription newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|CapitalSubscription newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|CapitalSubscription query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|CapitalSubscription whereActualAmountPaid($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|CapitalSubscription whereAmountSubscribed($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|CapitalSubscription whereCode($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|CapitalSubscription whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|CapitalSubscription whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|CapitalSubscription whereInitialAmountPaid($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|CapitalSubscription whereIsActive($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|CapitalSubscription whereMemberId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|CapitalSubscription whereMonthlyPayment($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|CapitalSubscription whereNumberOfShares($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|CapitalSubscription whereNumberOfTerms($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|CapitalSubscription whereOutstandingBalance($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|CapitalSubscription whereParValue($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|CapitalSubscription whereTotalAmountPaid($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|CapitalSubscription whereTransactionDate($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|CapitalSubscription whereUpdatedAt($value)
 * @mixin \Eloquent
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
            get: fn () => $this->number_of_shares * $this->par_value
        );
    }
}
