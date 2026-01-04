<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * @property int $id
 * @property string $name
 * @property int $member_type_id
 * @property \Carbon\CarbonImmutable|null $created_at
 * @property \Carbon\CarbonImmutable|null $updated_at
 * @method static \Illuminate\Database\Eloquent\Builder<static>|MemberSubtype newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|MemberSubtype newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|MemberSubtype query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|MemberSubtype whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|MemberSubtype whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|MemberSubtype whereMemberTypeId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|MemberSubtype whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|MemberSubtype whereUpdatedAt($value)
 * @mixin \Eloquent
 */
class MemberSubtype extends Model
{
    use HasFactory;
}
