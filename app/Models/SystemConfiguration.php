<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * @mixin IdeHelperSystemConfiguration
 */
class SystemConfiguration extends Model
{
    use HasFactory;

    protected $casts = [
        'content' => 'array',
    ];
}
