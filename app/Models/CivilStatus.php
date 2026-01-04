<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * @property int $id
 * @property string $name
 * @property \Carbon\CarbonImmutable|null $created_at
 * @property \Carbon\CarbonImmutable|null $updated_at
 * @method static \Illuminate\Database\Eloquent\Builder<static>|CivilStatus newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|CivilStatus newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|CivilStatus query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|CivilStatus whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|CivilStatus whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|CivilStatus whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|CivilStatus whereUpdatedAt($value)
 * @mixin \Eloquent
 */
class CivilStatus extends Model
{
    use HasFactory;
}
