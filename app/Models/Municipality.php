<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * @property int $id
 * @property int|null $province_id
 * @property string|null $name
 * @property-read \App\Models\Province|null $province
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Municipality newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Municipality newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Municipality query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Municipality whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Municipality whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Municipality whereProvinceId($value)
 * @mixin \Eloquent
 */
class Municipality extends Model
{
    use HasFactory;

    public function province()
    {
        return $this->belongsTo(Province::class);
    }
}
