<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

/**
 * @mixin IdeHelperSavingsAccount
 */
class SavingsAccount extends Account
{
    use HasFactory;

    protected $casts = [];

    public function savings()
    {
        return $this->hasMany(Saving::class);
    }

    public function savings_no_interest(): HasMany
    {
        return $this->hasMany(Saving::class)->whereNull('interest_date');
    }

    public function savings_unaccrued(): HasMany
    {
        return $this->hasMany(Saving::class)->where('accrued', false);
    }

    public function member()
    {
        return $this->belongsTo(Member::class);
    }

    protected static function boot()
    {
        parent::boot();
        static::addGlobalScope('member_regular_savings', function ($query) {
            $query->whereNotNull('member_id')->whereTag('regular_savings');
        });
    }
}
