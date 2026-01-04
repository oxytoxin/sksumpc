<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * @property int $id
 * @property string $name
 * @property \Carbon\CarbonImmutable|null $created_at
 * @property \Carbon\CarbonImmutable|null $updated_at
 * @method static \Illuminate\Database\Eloquent\Builder<static>|DisapprovalReason newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|DisapprovalReason newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|DisapprovalReason query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|DisapprovalReason whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|DisapprovalReason whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|DisapprovalReason whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|DisapprovalReason whereUpdatedAt($value)
 * @mixin \Eloquent
 */
class DisapprovalReason extends Model
{
    use HasFactory;
}
