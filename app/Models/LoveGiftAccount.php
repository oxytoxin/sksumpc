<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;

/**
 * @mixin IdeHelperLoveGiftAccount
 */
class LoveGiftAccount extends Account
{
    use HasFactory;

    public function member()
    {
        return $this->belongsTo(Member::class);
    }

    protected static function boot()
    {
        parent::boot();
        static::addGlobalScope('member_love_gift_savings', function ($query) {
            $query->whereNotNull('member_id')->whereTag('love_gift_savings');
        });
    }
}
