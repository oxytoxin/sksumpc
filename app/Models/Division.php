<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

/**
 * @property int $id
 * @property string $name
 * @property \Carbon\CarbonImmutable|null $created_at
 * @property \Carbon\CarbonImmutable|null $updated_at
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Member> $members
 * @property-read int|null $members_count
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Division newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Division newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Division query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Division whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Division whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Division whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Division whereUpdatedAt($value)
 * @mixin \Eloquent
 */
class Division extends Model
{
    use HasFactory;

    public function members(): HasMany
    {
        return $this->hasMany(Member::class);
    }
}
