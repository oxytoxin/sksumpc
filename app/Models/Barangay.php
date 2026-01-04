<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * @property int $id
 * @property int $municipality_id
 * @property string $name
 * @property-read \App\Models\Municipality $municipality
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Barangay newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Barangay newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Barangay query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Barangay whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Barangay whereMunicipalityId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Barangay whereName($value)
 * @mixin \Eloquent
 */
class Barangay extends Model
{
    use HasFactory;

    public function municipality()
    {
        return $this->belongsTo(Municipality::class);
    }
}
