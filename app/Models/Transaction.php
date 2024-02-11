<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Transaction extends Model
{
    use HasFactory;

    protected $casts = [
        'credit' => 'decimal:4',
        'debit' => 'decimal:4',
    ];

    public function account()
    {
        return $this->belongsTo(Account::class);
    }

    public function transaction_type()
    {
        return $this->belongsTo(TransactionType::class);
    }
}
