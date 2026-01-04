<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * @property int $id
 * @property string $name
 * @property \Carbon\CarbonImmutable|null $created_at
 * @property \Carbon\CarbonImmutable|null $updated_at
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PaymentType newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PaymentType newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PaymentType query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PaymentType whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PaymentType whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PaymentType whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PaymentType whereUpdatedAt($value)
 * @mixin \Eloquent
 */
class PaymentType extends Model
{
    use HasFactory;
}
