<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;

/**
 * @property int $id
 * @property int $account_type_id
 * @property int|null $member_id
 * @property int|null $parent_id
 * @property string $name
 * @property string $number
 * @property string|null $tag
 * @property string|null $accountable_type
 * @property int|null $accountable_id
 * @property \Carbon\CarbonImmutable|null $created_at
 * @property \Carbon\CarbonImmutable|null $updated_at
 * @property int $show_sum
 * @property string $sum_description
 * @property int $sort
 * @property-read \Staudenmeir\LaravelAdjacencyList\Eloquent\Collection<int, \App\Models\TimeDepositAccount> $children
 * @property-read int|null $children_count
 * @property-read \App\Models\TimeDeposit|null $original_time_deposit
 * @property-read \App\Models\TimeDepositAccount|null $parent
 * @property-read \App\Models\TimeDeposit|null $time_deposit
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\TimeDeposit> $time_deposits
 * @property-read int|null $time_deposits_count
 * @property-read int $depth
 * @property-read string $path
 * @property-read string $fullname
 * @property-read \Staudenmeir\LaravelAdjacencyList\Eloquent\Collection<int, \App\Models\TimeDepositAccount> $ancestors The model's recursive parents.
 * @property-read int|null $ancestors_count
 * @property-read \Staudenmeir\LaravelAdjacencyList\Eloquent\Collection<int, \App\Models\TimeDepositAccount> $ancestorsAndSelf The model's recursive parents and itself.
 * @property-read int|null $ancestors_and_self_count
 * @property-read \Staudenmeir\LaravelAdjacencyList\Eloquent\Collection<int, \App\Models\TimeDepositAccount> $bloodline The model's ancestors, descendants and itself.
 * @property-read int|null $bloodline_count
 * @property-read \Staudenmeir\LaravelAdjacencyList\Eloquent\Collection<int, \App\Models\TimeDepositAccount> $childrenAndSelf The model's direct children and itself.
 * @property-read int|null $children_and_self_count
 * @property-read \Staudenmeir\LaravelAdjacencyList\Eloquent\Collection<int, \App\Models\TimeDepositAccount> $descendants The model's recursive children.
 * @property-read int|null $descendants_count
 * @property-read \Staudenmeir\LaravelAdjacencyList\Eloquent\Collection<int, \App\Models\TimeDepositAccount> $descendantsAndSelf The model's recursive children and itself.
 * @property-read int|null $descendants_and_self_count
 * @property-read \Staudenmeir\LaravelAdjacencyList\Eloquent\Collection<int, \App\Models\TimeDepositAccount> $parentAndSelf The model's direct parent and itself.
 * @property-read int|null $parent_and_self_count
 * @property-read \App\Models\TimeDepositAccount|null $rootAncestor The model's topmost parent.
 * @property-read \Staudenmeir\LaravelAdjacencyList\Eloquent\Collection<int, \App\Models\TimeDepositAccount> $siblings The parent's other children.
 * @property-read int|null $siblings_count
 * @property-read \Staudenmeir\LaravelAdjacencyList\Eloquent\Collection<int, \App\Models\TimeDepositAccount> $siblingsAndSelf All the parent's children.
 * @property-read int|null $siblings_and_self_count
 * @method static \Staudenmeir\LaravelAdjacencyList\Eloquent\Collection<int, static> all($columns = ['*'])
 * @method static \Staudenmeir\LaravelAdjacencyList\Eloquent\Builder<static>|TimeDepositAccount breadthFirst()
 * @method static \Staudenmeir\LaravelAdjacencyList\Eloquent\Builder<static>|TimeDepositAccount depthFirst()
 * @method static \Staudenmeir\LaravelAdjacencyList\Eloquent\Builder<static>|TimeDepositAccount doesntHaveChildren()
 * @method static \Staudenmeir\LaravelAdjacencyList\Eloquent\Collection<int, static> get($columns = ['*'])
 * @method static \Staudenmeir\LaravelAdjacencyList\Eloquent\Builder<static>|TimeDepositAccount getExpressionGrammar()
 * @method static \Staudenmeir\LaravelAdjacencyList\Eloquent\Builder<static>|TimeDepositAccount hasChildren()
 * @method static \Staudenmeir\LaravelAdjacencyList\Eloquent\Builder<static>|TimeDepositAccount hasParent()
 * @method static \Staudenmeir\LaravelAdjacencyList\Eloquent\Builder<static>|TimeDepositAccount isLeaf()
 * @method static \Staudenmeir\LaravelAdjacencyList\Eloquent\Builder<static>|TimeDepositAccount isRoot()
 * @method static \Staudenmeir\LaravelAdjacencyList\Eloquent\Builder<static>|TimeDepositAccount newModelQuery()
 * @method static \Staudenmeir\LaravelAdjacencyList\Eloquent\Builder<static>|TimeDepositAccount newQuery()
 * @method static \Staudenmeir\LaravelAdjacencyList\Eloquent\Builder<static>|TimeDepositAccount query()
 * @method static \Staudenmeir\LaravelAdjacencyList\Eloquent\Builder<static>|TimeDepositAccount tree($maxDepth = null)
 * @method static \Staudenmeir\LaravelAdjacencyList\Eloquent\Builder<static>|TimeDepositAccount treeOf(\Illuminate\Database\Eloquent\Model|callable $constraint, $maxDepth = null)
 * @method static \Staudenmeir\LaravelAdjacencyList\Eloquent\Builder<static>|TimeDepositAccount whereAccountTypeId($value)
 * @method static \Staudenmeir\LaravelAdjacencyList\Eloquent\Builder<static>|TimeDepositAccount whereAccountableId($value)
 * @method static \Staudenmeir\LaravelAdjacencyList\Eloquent\Builder<static>|TimeDepositAccount whereAccountableType($value)
 * @method static \Staudenmeir\LaravelAdjacencyList\Eloquent\Builder<static>|TimeDepositAccount whereCreatedAt($value)
 * @method static \Staudenmeir\LaravelAdjacencyList\Eloquent\Builder<static>|TimeDepositAccount whereDepth($operator, $value = null)
 * @method static \Staudenmeir\LaravelAdjacencyList\Eloquent\Builder<static>|TimeDepositAccount whereId($value)
 * @method static \Staudenmeir\LaravelAdjacencyList\Eloquent\Builder<static>|TimeDepositAccount whereMemberId($value)
 * @method static \Staudenmeir\LaravelAdjacencyList\Eloquent\Builder<static>|TimeDepositAccount whereName($value)
 * @method static \Staudenmeir\LaravelAdjacencyList\Eloquent\Builder<static>|TimeDepositAccount whereNumber($value)
 * @method static \Staudenmeir\LaravelAdjacencyList\Eloquent\Builder<static>|TimeDepositAccount whereParentId($value)
 * @method static \Staudenmeir\LaravelAdjacencyList\Eloquent\Builder<static>|TimeDepositAccount whereShowSum($value)
 * @method static \Staudenmeir\LaravelAdjacencyList\Eloquent\Builder<static>|TimeDepositAccount whereSort($value)
 * @method static \Staudenmeir\LaravelAdjacencyList\Eloquent\Builder<static>|TimeDepositAccount whereSumDescription($value)
 * @method static \Staudenmeir\LaravelAdjacencyList\Eloquent\Builder<static>|TimeDepositAccount whereTag($value)
 * @method static \Staudenmeir\LaravelAdjacencyList\Eloquent\Builder<static>|TimeDepositAccount whereUpdatedAt($value)
 * @method static \Staudenmeir\LaravelAdjacencyList\Eloquent\Builder<static>|TimeDepositAccount withCode()
 * @method static \Staudenmeir\LaravelAdjacencyList\Eloquent\Builder<static>|TimeDepositAccount withGlobalScopes(array $scopes)
 * @method static \Staudenmeir\LaravelAdjacencyList\Eloquent\Builder<static>|TimeDepositAccount withRelationshipExpression($direction, callable $constraint, $initialDepth, $from = null, $maxDepth = null)
 * @mixin \Eloquent
 */
class TimeDepositAccount extends Account
{
    use HasFactory;

    protected static function boot()
    {
        parent::boot();
        static::addGlobalScope('member_time_deposits', function ($query) {
            $query->whereNotNull('member_id')->whereTag('member_time_deposits');
        });
    }

    public function time_deposits()
    {
        return $this->hasMany(TimeDeposit::class)->orderBy('transaction_date');
    }

    public function time_deposit()
    {
        return $this->hasOne(TimeDeposit::class)->latestOfMany('transaction_date');
    }

    public function original_time_deposit()
    {
        return $this->hasOne(TimeDeposit::class)->oldestOfMany('transaction_date');
    }
}
