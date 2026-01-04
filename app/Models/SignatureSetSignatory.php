<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * @property int $id
 * @property int $signature_set_id
 * @property string $action
 * @property int $user_id
 * @property string $designation
 * @property \Carbon\CarbonImmutable|null $created_at
 * @property \Carbon\CarbonImmutable|null $updated_at
 * @property-read \App\Models\User $user
 * @method static \Illuminate\Database\Eloquent\Builder<static>|SignatureSetSignatory newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|SignatureSetSignatory newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|SignatureSetSignatory query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|SignatureSetSignatory whereAction($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|SignatureSetSignatory whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|SignatureSetSignatory whereDesignation($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|SignatureSetSignatory whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|SignatureSetSignatory whereSignatureSetId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|SignatureSetSignatory whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|SignatureSetSignatory whereUserId($value)
 * @mixin \Eloquent
 */
class SignatureSetSignatory extends Model
{
    use HasFactory;

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
