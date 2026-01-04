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
 * @property-read \Staudenmeir\LaravelAdjacencyList\Eloquent\Collection<int, \App\Models\CashCollectibleAccount> $children
 * @property-read int|null $children_count
 * @property-read \App\Models\CashCollectibleAccount|null $parent
 * @property-read int $depth
 * @property-read string $path
 * @property-read string $fullname
 * @property-read \Staudenmeir\LaravelAdjacencyList\Eloquent\Collection<int, \App\Models\CashCollectibleAccount> $ancestors The model's recursive parents.
 * @property-read int|null $ancestors_count
 * @property-read \Staudenmeir\LaravelAdjacencyList\Eloquent\Collection<int, \App\Models\CashCollectibleAccount> $ancestorsAndSelf The model's recursive parents and itself.
 * @property-read int|null $ancestors_and_self_count
 * @property-read \Staudenmeir\LaravelAdjacencyList\Eloquent\Collection<int, \App\Models\CashCollectibleAccount> $bloodline The model's ancestors, descendants and itself.
 * @property-read int|null $bloodline_count
 * @property-read \Staudenmeir\LaravelAdjacencyList\Eloquent\Collection<int, \App\Models\CashCollectibleAccount> $childrenAndSelf The model's direct children and itself.
 * @property-read int|null $children_and_self_count
 * @property-read \Staudenmeir\LaravelAdjacencyList\Eloquent\Collection<int, \App\Models\CashCollectibleAccount> $descendants The model's recursive children.
 * @property-read int|null $descendants_count
 * @property-read \Staudenmeir\LaravelAdjacencyList\Eloquent\Collection<int, \App\Models\CashCollectibleAccount> $descendantsAndSelf The model's recursive children and itself.
 * @property-read int|null $descendants_and_self_count
 * @property-read \Staudenmeir\LaravelAdjacencyList\Eloquent\Collection<int, \App\Models\CashCollectibleAccount> $parentAndSelf The model's direct parent and itself.
 * @property-read int|null $parent_and_self_count
 * @property-read \App\Models\CashCollectibleAccount|null $rootAncestor The model's topmost parent.
 * @property-read \Staudenmeir\LaravelAdjacencyList\Eloquent\Collection<int, \App\Models\CashCollectibleAccount> $siblings The parent's other children.
 * @property-read int|null $siblings_count
 * @property-read \Staudenmeir\LaravelAdjacencyList\Eloquent\Collection<int, \App\Models\CashCollectibleAccount> $siblingsAndSelf All the parent's children.
 * @property-read int|null $siblings_and_self_count
 * @method static \Staudenmeir\LaravelAdjacencyList\Eloquent\Collection<int, static> all($columns = ['*'])
 * @method static \Staudenmeir\LaravelAdjacencyList\Eloquent\Builder<static>|CashCollectibleAccount breadthFirst()
 * @method static \Staudenmeir\LaravelAdjacencyList\Eloquent\Builder<static>|CashCollectibleAccount depthFirst()
 * @method static \Staudenmeir\LaravelAdjacencyList\Eloquent\Builder<static>|CashCollectibleAccount doesntHaveChildren()
 * @method static \Staudenmeir\LaravelAdjacencyList\Eloquent\Collection<int, static> get($columns = ['*'])
 * @method static \Staudenmeir\LaravelAdjacencyList\Eloquent\Builder<static>|CashCollectibleAccount getExpressionGrammar()
 * @method static \Staudenmeir\LaravelAdjacencyList\Eloquent\Builder<static>|CashCollectibleAccount hasChildren()
 * @method static \Staudenmeir\LaravelAdjacencyList\Eloquent\Builder<static>|CashCollectibleAccount hasParent()
 * @method static \Staudenmeir\LaravelAdjacencyList\Eloquent\Builder<static>|CashCollectibleAccount isLeaf()
 * @method static \Staudenmeir\LaravelAdjacencyList\Eloquent\Builder<static>|CashCollectibleAccount isRoot()
 * @method static \Staudenmeir\LaravelAdjacencyList\Eloquent\Builder<static>|CashCollectibleAccount newModelQuery()
 * @method static \Staudenmeir\LaravelAdjacencyList\Eloquent\Builder<static>|CashCollectibleAccount newQuery()
 * @method static \Staudenmeir\LaravelAdjacencyList\Eloquent\Builder<static>|CashCollectibleAccount query()
 * @method static \Staudenmeir\LaravelAdjacencyList\Eloquent\Builder<static>|CashCollectibleAccount tree($maxDepth = null)
 * @method static \Staudenmeir\LaravelAdjacencyList\Eloquent\Builder<static>|CashCollectibleAccount treeOf(\Illuminate\Database\Eloquent\Model|callable $constraint, $maxDepth = null)
 * @method static \Staudenmeir\LaravelAdjacencyList\Eloquent\Builder<static>|CashCollectibleAccount whereAccountTypeId($value)
 * @method static \Staudenmeir\LaravelAdjacencyList\Eloquent\Builder<static>|CashCollectibleAccount whereAccountableId($value)
 * @method static \Staudenmeir\LaravelAdjacencyList\Eloquent\Builder<static>|CashCollectibleAccount whereAccountableType($value)
 * @method static \Staudenmeir\LaravelAdjacencyList\Eloquent\Builder<static>|CashCollectibleAccount whereCreatedAt($value)
 * @method static \Staudenmeir\LaravelAdjacencyList\Eloquent\Builder<static>|CashCollectibleAccount whereDepth($operator, $value = null)
 * @method static \Staudenmeir\LaravelAdjacencyList\Eloquent\Builder<static>|CashCollectibleAccount whereId($value)
 * @method static \Staudenmeir\LaravelAdjacencyList\Eloquent\Builder<static>|CashCollectibleAccount whereMemberId($value)
 * @method static \Staudenmeir\LaravelAdjacencyList\Eloquent\Builder<static>|CashCollectibleAccount whereName($value)
 * @method static \Staudenmeir\LaravelAdjacencyList\Eloquent\Builder<static>|CashCollectibleAccount whereNumber($value)
 * @method static \Staudenmeir\LaravelAdjacencyList\Eloquent\Builder<static>|CashCollectibleAccount whereParentId($value)
 * @method static \Staudenmeir\LaravelAdjacencyList\Eloquent\Builder<static>|CashCollectibleAccount whereShowSum($value)
 * @method static \Staudenmeir\LaravelAdjacencyList\Eloquent\Builder<static>|CashCollectibleAccount whereSort($value)
 * @method static \Staudenmeir\LaravelAdjacencyList\Eloquent\Builder<static>|CashCollectibleAccount whereSumDescription($value)
 * @method static \Staudenmeir\LaravelAdjacencyList\Eloquent\Builder<static>|CashCollectibleAccount whereTag($value)
 * @method static \Staudenmeir\LaravelAdjacencyList\Eloquent\Builder<static>|CashCollectibleAccount whereUpdatedAt($value)
 * @method static \Staudenmeir\LaravelAdjacencyList\Eloquent\Builder<static>|CashCollectibleAccount withCode()
 * @method static \Staudenmeir\LaravelAdjacencyList\Eloquent\Builder<static>|CashCollectibleAccount withGlobalScopes(array $scopes)
 * @method static \Staudenmeir\LaravelAdjacencyList\Eloquent\Builder<static>|CashCollectibleAccount withRelationshipExpression($direction, callable $constraint, $initialDepth, $from = null, $maxDepth = null)
 * @mixin \Eloquent
 */
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
