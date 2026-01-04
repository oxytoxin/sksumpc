<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasMany;

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
 * @property-read \Staudenmeir\LaravelAdjacencyList\Eloquent\Collection<int, \App\Models\SavingsAccount> $children
 * @property-read int|null $children_count
 * @property-read \App\Models\Member|null $member
 * @property-read \App\Models\SavingsAccount|null $parent
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Saving> $savings
 * @property-read int|null $savings_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Saving> $savings_no_interest
 * @property-read int|null $savings_no_interest_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Saving> $savings_unaccrued
 * @property-read int|null $savings_unaccrued_count
 * @property-read int $depth
 * @property-read string $path
 * @property-read string $fullname
 * @property-read \Staudenmeir\LaravelAdjacencyList\Eloquent\Collection<int, \App\Models\SavingsAccount> $ancestors The model's recursive parents.
 * @property-read int|null $ancestors_count
 * @property-read \Staudenmeir\LaravelAdjacencyList\Eloquent\Collection<int, \App\Models\SavingsAccount> $ancestorsAndSelf The model's recursive parents and itself.
 * @property-read int|null $ancestors_and_self_count
 * @property-read \Staudenmeir\LaravelAdjacencyList\Eloquent\Collection<int, \App\Models\SavingsAccount> $bloodline The model's ancestors, descendants and itself.
 * @property-read int|null $bloodline_count
 * @property-read \Staudenmeir\LaravelAdjacencyList\Eloquent\Collection<int, \App\Models\SavingsAccount> $childrenAndSelf The model's direct children and itself.
 * @property-read int|null $children_and_self_count
 * @property-read \Staudenmeir\LaravelAdjacencyList\Eloquent\Collection<int, \App\Models\SavingsAccount> $descendants The model's recursive children.
 * @property-read int|null $descendants_count
 * @property-read \Staudenmeir\LaravelAdjacencyList\Eloquent\Collection<int, \App\Models\SavingsAccount> $descendantsAndSelf The model's recursive children and itself.
 * @property-read int|null $descendants_and_self_count
 * @property-read \Staudenmeir\LaravelAdjacencyList\Eloquent\Collection<int, \App\Models\SavingsAccount> $parentAndSelf The model's direct parent and itself.
 * @property-read int|null $parent_and_self_count
 * @property-read \App\Models\SavingsAccount|null $rootAncestor The model's topmost parent.
 * @property-read \Staudenmeir\LaravelAdjacencyList\Eloquent\Collection<int, \App\Models\SavingsAccount> $siblings The parent's other children.
 * @property-read int|null $siblings_count
 * @property-read \Staudenmeir\LaravelAdjacencyList\Eloquent\Collection<int, \App\Models\SavingsAccount> $siblingsAndSelf All the parent's children.
 * @property-read int|null $siblings_and_self_count
 * @method static \Staudenmeir\LaravelAdjacencyList\Eloquent\Collection<int, static> all($columns = ['*'])
 * @method static \Staudenmeir\LaravelAdjacencyList\Eloquent\Builder<static>|SavingsAccount breadthFirst()
 * @method static \Staudenmeir\LaravelAdjacencyList\Eloquent\Builder<static>|SavingsAccount depthFirst()
 * @method static \Staudenmeir\LaravelAdjacencyList\Eloquent\Builder<static>|SavingsAccount doesntHaveChildren()
 * @method static \Staudenmeir\LaravelAdjacencyList\Eloquent\Collection<int, static> get($columns = ['*'])
 * @method static \Staudenmeir\LaravelAdjacencyList\Eloquent\Builder<static>|SavingsAccount getExpressionGrammar()
 * @method static \Staudenmeir\LaravelAdjacencyList\Eloquent\Builder<static>|SavingsAccount hasChildren()
 * @method static \Staudenmeir\LaravelAdjacencyList\Eloquent\Builder<static>|SavingsAccount hasParent()
 * @method static \Staudenmeir\LaravelAdjacencyList\Eloquent\Builder<static>|SavingsAccount isLeaf()
 * @method static \Staudenmeir\LaravelAdjacencyList\Eloquent\Builder<static>|SavingsAccount isRoot()
 * @method static \Staudenmeir\LaravelAdjacencyList\Eloquent\Builder<static>|SavingsAccount newModelQuery()
 * @method static \Staudenmeir\LaravelAdjacencyList\Eloquent\Builder<static>|SavingsAccount newQuery()
 * @method static \Staudenmeir\LaravelAdjacencyList\Eloquent\Builder<static>|SavingsAccount query()
 * @method static \Staudenmeir\LaravelAdjacencyList\Eloquent\Builder<static>|SavingsAccount tree($maxDepth = null)
 * @method static \Staudenmeir\LaravelAdjacencyList\Eloquent\Builder<static>|SavingsAccount treeOf(\Illuminate\Database\Eloquent\Model|callable $constraint, $maxDepth = null)
 * @method static \Staudenmeir\LaravelAdjacencyList\Eloquent\Builder<static>|SavingsAccount whereAccountTypeId($value)
 * @method static \Staudenmeir\LaravelAdjacencyList\Eloquent\Builder<static>|SavingsAccount whereAccountableId($value)
 * @method static \Staudenmeir\LaravelAdjacencyList\Eloquent\Builder<static>|SavingsAccount whereAccountableType($value)
 * @method static \Staudenmeir\LaravelAdjacencyList\Eloquent\Builder<static>|SavingsAccount whereCreatedAt($value)
 * @method static \Staudenmeir\LaravelAdjacencyList\Eloquent\Builder<static>|SavingsAccount whereDepth($operator, $value = null)
 * @method static \Staudenmeir\LaravelAdjacencyList\Eloquent\Builder<static>|SavingsAccount whereId($value)
 * @method static \Staudenmeir\LaravelAdjacencyList\Eloquent\Builder<static>|SavingsAccount whereMemberId($value)
 * @method static \Staudenmeir\LaravelAdjacencyList\Eloquent\Builder<static>|SavingsAccount whereName($value)
 * @method static \Staudenmeir\LaravelAdjacencyList\Eloquent\Builder<static>|SavingsAccount whereNumber($value)
 * @method static \Staudenmeir\LaravelAdjacencyList\Eloquent\Builder<static>|SavingsAccount whereParentId($value)
 * @method static \Staudenmeir\LaravelAdjacencyList\Eloquent\Builder<static>|SavingsAccount whereShowSum($value)
 * @method static \Staudenmeir\LaravelAdjacencyList\Eloquent\Builder<static>|SavingsAccount whereSort($value)
 * @method static \Staudenmeir\LaravelAdjacencyList\Eloquent\Builder<static>|SavingsAccount whereSumDescription($value)
 * @method static \Staudenmeir\LaravelAdjacencyList\Eloquent\Builder<static>|SavingsAccount whereTag($value)
 * @method static \Staudenmeir\LaravelAdjacencyList\Eloquent\Builder<static>|SavingsAccount whereUpdatedAt($value)
 * @method static \Staudenmeir\LaravelAdjacencyList\Eloquent\Builder<static>|SavingsAccount withCode()
 * @method static \Staudenmeir\LaravelAdjacencyList\Eloquent\Builder<static>|SavingsAccount withGlobalScopes(array $scopes)
 * @method static \Staudenmeir\LaravelAdjacencyList\Eloquent\Builder<static>|SavingsAccount withRelationshipExpression($direction, callable $constraint, $initialDepth, $from = null, $maxDepth = null)
 * @mixin \Eloquent
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
