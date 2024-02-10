<?php

namespace App\Models;

class MemberAccount extends Account
{
    protected static function boot()
    {
        parent::boot();
        static::addGlobalScope('belongsToMember', function ($query) {
            $query->whereNotNull('member_id');
        });
    }
}
