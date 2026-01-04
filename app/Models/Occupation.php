<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * @property int $id
 * @property string $name
 * @property \Carbon\CarbonImmutable|null $created_at
 * @property \Carbon\CarbonImmutable|null $updated_at
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Occupation newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Occupation newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Occupation query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Occupation whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Occupation whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Occupation whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Occupation whereUpdatedAt($value)
 * @mixin \Eloquent
 */
class Occupation extends Model
{
    use HasFactory;
}
