<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * @mixin IdeHelperProvince
 */
class Province extends Model
{
    use HasFactory;

    public function region()
    {
        return $this->belongsTo(Region::class);
    }
}
