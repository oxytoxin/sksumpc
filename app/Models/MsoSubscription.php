<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * @property int $id
 * @property int $type
 * @property int $account_id
 * @property int|null $member_id
 * @property string $payee
 * @property numeric $amount
 * @property \Carbon\CarbonImmutable|null $created_at
 * @property \Carbon\CarbonImmutable|null $updated_at
 * @method static \Illuminate\Database\Eloquent\Builder<static>|MsoSubscription newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|MsoSubscription newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|MsoSubscription query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|MsoSubscription whereAccountId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|MsoSubscription whereAmount($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|MsoSubscription whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|MsoSubscription whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|MsoSubscription whereMemberId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|MsoSubscription wherePayee($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|MsoSubscription whereType($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|MsoSubscription whereUpdatedAt($value)
 * @mixin \Eloquent
 */
class MsoSubscription extends Model
{
    use HasFactory;
}
