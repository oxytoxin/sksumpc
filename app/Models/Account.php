<?php

namespace App\Models;

use App\Oxytoxin\Traits\CreatesChildren;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * @mixin IdeHelperAccount
 */
class Account extends Model
{
    use HasFactory, CreatesChildren;
}
