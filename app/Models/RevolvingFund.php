<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\MorphTo;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * @property int $id
 * @property numeric|null $deposit
 * @property numeric|null $withdrawal
 * @property string $reference_number
 * @property \Carbon\CarbonImmutable $transaction_date
 * @property int $cashier_id
 * @property string|null $withdrawable_type
 * @property int|null $withdrawable_id
 * @property \Carbon\CarbonImmutable|null $deleted_at
 * @property \Carbon\CarbonImmutable|null $created_at
 * @property \Carbon\CarbonImmutable|null $updated_at
 * @property-read \App\Models\User $cashier
 * @property-read Model|\Eloquent|null $withdrawable
 * @method static \Illuminate\Database\Eloquent\Builder<static>|RevolvingFund newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|RevolvingFund newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|RevolvingFund onlyTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|RevolvingFund query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|RevolvingFund whereCashierId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|RevolvingFund whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|RevolvingFund whereDeletedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|RevolvingFund whereDeposit($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|RevolvingFund whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|RevolvingFund whereReferenceNumber($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|RevolvingFund whereTransactionDate($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|RevolvingFund whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|RevolvingFund whereWithdrawableId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|RevolvingFund whereWithdrawableType($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|RevolvingFund whereWithdrawal($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|RevolvingFund withTrashed(bool $withTrashed = true)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|RevolvingFund withoutTrashed()
 * @mixin \Eloquent
 */
class RevolvingFund extends Model
{
    use HasFactory, SoftDeletes;

    protected $casts = [
        'transaction_date' => 'immutable_datetime',
    ];

    public function cashier()
    {
        return $this->belongsTo(User::class, 'cashier_id');
    }

    public function withdrawable(): MorphTo
    {
        return $this->morphTo();
    }

    protected static function booted()
    {
        static::creating(function (RevolvingFund $revolving_fund) {
            $revolving_fund->cashier_id ??= auth()->id();
        });
    }
}
