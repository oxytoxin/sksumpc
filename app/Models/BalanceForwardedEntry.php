<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * @property int $id
 * @property int $balance_forwarded_summary_id
 * @property int $account_id
 * @property numeric|null $credit
 * @property numeric|null $debit
 * @property \Carbon\CarbonImmutable|null $created_at
 * @property \Carbon\CarbonImmutable|null $updated_at
 * @property-read \App\Models\Account $account
 * @property-read \App\Models\BalanceForwardedSummary $balance_forwarded_summary
 * @method static \Illuminate\Database\Eloquent\Builder<static>|BalanceForwardedEntry newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|BalanceForwardedEntry newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|BalanceForwardedEntry query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|BalanceForwardedEntry whereAccountId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|BalanceForwardedEntry whereBalanceForwardedSummaryId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|BalanceForwardedEntry whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|BalanceForwardedEntry whereCredit($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|BalanceForwardedEntry whereDebit($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|BalanceForwardedEntry whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|BalanceForwardedEntry whereUpdatedAt($value)
 * @mixin \Eloquent
 */
class BalanceForwardedEntry extends Model
{
    use HasFactory;

    protected $casts = [
        'credit' => 'decimal:4',
        'debit' => 'decimal:4',
    ];

    public function balance_forwarded_summary()
    {
        return $this->belongsTo(BalanceForwardedSummary::class);
    }

    public function account()
    {
        return $this->belongsTo(Account::class)->tree();
    }
}
