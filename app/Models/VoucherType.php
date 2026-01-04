<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * @property int $id
 * @property string $name
 * @property \Carbon\CarbonImmutable|null $created_at
 * @property \Carbon\CarbonImmutable|null $updated_at
 * @method static \Illuminate\Database\Eloquent\Builder<static>|VoucherType newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|VoucherType newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|VoucherType query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|VoucherType whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|VoucherType whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|VoucherType whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|VoucherType whereUpdatedAt($value)
 * @mixin \Eloquent
 */
class VoucherType extends Model
{
    use HasFactory;
}
