<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * @property int $id
 * @property string $name
 * @property int $debit_operator
 * @property int $credit_operator
 * @property \Carbon\CarbonImmutable|null $created_at
 * @property \Carbon\CarbonImmutable|null $updated_at
 * @property-read \Staudenmeir\LaravelAdjacencyList\Eloquent\Collection<int, \App\Models\Account> $accounts
 * @property-read int|null $accounts_count
 * @method static \Illuminate\Database\Eloquent\Builder<static>|AccountType newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|AccountType newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|AccountType query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|AccountType whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|AccountType whereCreditOperator($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|AccountType whereDebitOperator($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|AccountType whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|AccountType whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|AccountType whereUpdatedAt($value)
 * @mixin \Eloquent
 */
class AccountType extends Model
{
    use HasFactory;

    public function accounts()
    {
        return $this->hasMany(Account::class);
    }
}
