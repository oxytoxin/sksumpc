<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CashCollectibleAccount extends Account
{
    use HasFactory;

    protected static function boot()
    {
        parent::boot();
        static::addGlobalScope('account_receivables', function ($query) {
            $query->whereNull('member_id')->whereNotNull('parent_id')->whereTag('account_receivables');
        });
    }
}
