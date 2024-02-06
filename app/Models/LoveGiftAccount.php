<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * @mixin IdeHelperLoveGiftAccount
 */
class LoveGiftAccount extends Model
{
    use HasFactory;

    public function member()
    {
        return $this->belongsTo(Member::class);
    }
}
