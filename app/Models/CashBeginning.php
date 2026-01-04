<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * @property int $id
 * @property numeric $amount
 * @property \Carbon\CarbonImmutable $transaction_date
 * @property int|null $cashier_id
 * @property \Carbon\CarbonImmutable|null $created_at
 * @property \Carbon\CarbonImmutable|null $updated_at
 * @property-read \App\Models\User|null $cashier
 * @method static \Illuminate\Database\Eloquent\Builder<static>|CashBeginning newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|CashBeginning newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|CashBeginning query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|CashBeginning whereAmount($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|CashBeginning whereCashierId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|CashBeginning whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|CashBeginning whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|CashBeginning whereTransactionDate($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|CashBeginning whereUpdatedAt($value)
 * @mixin \Eloquent
 */
class CashBeginning extends Model
{
    use HasFactory;

    protected $casts = [
        'amount' => 'decimal:4',
        'transaction_date' => 'immutable_date',
    ];

    public function cashier()
    {
        return $this->belongsTo(User::class, 'cashier_id');
    }
}
