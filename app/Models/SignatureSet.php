<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * @property int $id
 * @property string $name
 * @property \Carbon\CarbonImmutable|null $created_at
 * @property \Carbon\CarbonImmutable|null $updated_at
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\SignatureSetSignatory> $signatories
 * @property-read int|null $signatories_count
 * @method static \Illuminate\Database\Eloquent\Builder<static>|SignatureSet newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|SignatureSet newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|SignatureSet query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|SignatureSet whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|SignatureSet whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|SignatureSet whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|SignatureSet whereUpdatedAt($value)
 * @mixin \Eloquent
 */
class SignatureSet extends Model
{
    use HasFactory;

    public function signatories()
    {
        return $this->hasMany(SignatureSetSignatory::class);
    }
}
