<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * @property int $id
 * @property string $name
 * @property \Carbon\CarbonImmutable|null $created_at
 * @property \Carbon\CarbonImmutable|null $updated_at
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PatronageStatus newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PatronageStatus newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PatronageStatus query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PatronageStatus whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PatronageStatus whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PatronageStatus whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PatronageStatus whereUpdatedAt($value)
 * @mixin \Eloquent
 */
class PatronageStatus extends Model
{
    use HasFactory;
}
