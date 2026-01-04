<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * @property int $id
 * @property \Carbon\CarbonImmutable $generated_date
 * @property \Carbon\CarbonImmutable|null $created_at
 * @property \Carbon\CarbonImmutable|null $updated_at
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\BalanceForwardedEntry> $balance_forwarded_entries
 * @property-read int|null $balance_forwarded_entries_count
 * @method static \Illuminate\Database\Eloquent\Builder<static>|BalanceForwardedSummary newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|BalanceForwardedSummary newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|BalanceForwardedSummary query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|BalanceForwardedSummary whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|BalanceForwardedSummary whereGeneratedDate($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|BalanceForwardedSummary whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|BalanceForwardedSummary whereUpdatedAt($value)
 * @mixin \Eloquent
 */
class BalanceForwardedSummary extends Model
{
    use HasFactory;

    protected $casts = [
        'generated_date' => 'immutable_date',
    ];

    public function balance_forwarded_entries()
    {
        return $this->hasMany(BalanceForwardedEntry::class);
    }
}
