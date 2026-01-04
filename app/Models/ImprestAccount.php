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
 * @property-read \Staudenmeir\LaravelAdjacencyList\Eloquent\Collection<int, \App\Models\ImprestAccount> $children
 * @property-read int|null $children_count
 * @property-read \App\Models\Member|null $member
 * @property-read \App\Models\ImprestAccount|null $parent
 * @property-read int $depth
 * @property-read string $path
 * @property-read string $fullname
 * @property-read \Staudenmeir\LaravelAdjacencyList\Eloquent\Collection<int, \App\Models\ImprestAccount> $ancestors The model's recursive parents.
 * @property-read int|null $ancestors_count
 * @property-read \Staudenmeir\LaravelAdjacencyList\Eloquent\Collection<int, \App\Models\ImprestAccount> $ancestorsAndSelf The model's recursive parents and itself.
 * @property-read int|null $ancestors_and_self_count
 * @property-read \Staudenmeir\LaravelAdjacencyList\Eloquent\Collection<int, \App\Models\ImprestAccount> $bloodline The model's ancestors, descendants and itself.
 * @property-read int|null $bloodline_count
 * @property-read \Staudenmeir\LaravelAdjacencyList\Eloquent\Collection<int, \App\Models\ImprestAccount> $childrenAndSelf The model's direct children and itself.
 * @property-read int|null $children_and_self_count
 * @property-read \Staudenmeir\LaravelAdjacencyList\Eloquent\Collection<int, \App\Models\ImprestAccount> $descendants The model's recursive children.
 * @property-read int|null $descendants_count
 * @property-read \Staudenmeir\LaravelAdjacencyList\Eloquent\Collection<int, \App\Models\ImprestAccount> $descendantsAndSelf The model's recursive children and itself.
 * @property-read int|null $descendants_and_self_count
 * @property-read \Staudenmeir\LaravelAdjacencyList\Eloquent\Collection<int, \App\Models\ImprestAccount> $parentAndSelf The model's direct parent and itself.
 * @property-read int|null $parent_and_self_count
 * @property-read \App\Models\ImprestAccount|null $rootAncestor The model's topmost parent.
 * @property-read \Staudenmeir\LaravelAdjacencyList\Eloquent\Collection<int, \App\Models\ImprestAccount> $siblings The parent's other children.
 * @property-read int|null $siblings_count
 * @property-read \Staudenmeir\LaravelAdjacencyList\Eloquent\Collection<int, \App\Models\ImprestAccount> $siblingsAndSelf All the parent's children.
 * @property-read int|null $siblings_and_self_count
 * @method static \Staudenmeir\LaravelAdjacencyList\Eloquent\Collection<int, static> all($columns = ['*'])
 * @method static \Staudenmeir\LaravelAdjacencyList\Eloquent\Builder<static>|ImprestAccount breadthFirst()
 * @method static \Staudenmeir\LaravelAdjacencyList\Eloquent\Builder<static>|ImprestAccount depthFirst()
 * @method static \Staudenmeir\LaravelAdjacencyList\Eloquent\Builder<static>|ImprestAccount doesntHaveChildren()
 * @method static \Staudenmeir\LaravelAdjacencyList\Eloquent\Collection<int, static> get($columns = ['*'])
 * @method static \Staudenmeir\LaravelAdjacencyList\Eloquent\Builder<static>|ImprestAccount getExpressionGrammar()
 * @method static \Staudenmeir\LaravelAdjacencyList\Eloquent\Builder<static>|ImprestAccount hasChildren()
 * @method static \Staudenmeir\LaravelAdjacencyList\Eloquent\Builder<static>|ImprestAccount hasParent()
 * @method static \Staudenmeir\LaravelAdjacencyList\Eloquent\Builder<static>|ImprestAccount isLeaf()
 * @method static \Staudenmeir\LaravelAdjacencyList\Eloquent\Builder<static>|ImprestAccount isRoot()
 * @method static \Staudenmeir\LaravelAdjacencyList\Eloquent\Builder<static>|ImprestAccount newModelQuery()
 * @method static \Staudenmeir\LaravelAdjacencyList\Eloquent\Builder<static>|ImprestAccount newQuery()
 * @method static \Staudenmeir\LaravelAdjacencyList\Eloquent\Builder<static>|ImprestAccount query()
 * @method static \Staudenmeir\LaravelAdjacencyList\Eloquent\Builder<static>|ImprestAccount tree($maxDepth = null)
 * @method static \Staudenmeir\LaravelAdjacencyList\Eloquent\Builder<static>|ImprestAccount treeOf(\Illuminate\Database\Eloquent\Model|callable $constraint, $maxDepth = null)
 * @method static \Staudenmeir\LaravelAdjacencyList\Eloquent\Builder<static>|ImprestAccount whereAccountTypeId($value)
 * @method static \Staudenmeir\LaravelAdjacencyList\Eloquent\Builder<static>|ImprestAccount whereAccountableId($value)
 * @method static \Staudenmeir\LaravelAdjacencyList\Eloquent\Builder<static>|ImprestAccount whereAccountableType($value)
 * @method static \Staudenmeir\LaravelAdjacencyList\Eloquent\Builder<static>|ImprestAccount whereCreatedAt($value)
 * @method static \Staudenmeir\LaravelAdjacencyList\Eloquent\Builder<static>|ImprestAccount whereDepth($operator, $value = null)
 * @method static \Staudenmeir\LaravelAdjacencyList\Eloquent\Builder<static>|ImprestAccount whereId($value)
 * @method static \Staudenmeir\LaravelAdjacencyList\Eloquent\Builder<static>|ImprestAccount whereMemberId($value)
 * @method static \Staudenmeir\LaravelAdjacencyList\Eloquent\Builder<static>|ImprestAccount whereName($value)
 * @method static \Staudenmeir\LaravelAdjacencyList\Eloquent\Builder<static>|ImprestAccount whereNumber($value)
 * @method static \Staudenmeir\LaravelAdjacencyList\Eloquent\Builder<static>|ImprestAccount whereParentId($value)
 * @method static \Staudenmeir\LaravelAdjacencyList\Eloquent\Builder<static>|ImprestAccount whereShowSum($value)
 * @method static \Staudenmeir\LaravelAdjacencyList\Eloquent\Builder<static>|ImprestAccount whereSort($value)
 * @method static \Staudenmeir\LaravelAdjacencyList\Eloquent\Builder<static>|ImprestAccount whereSumDescription($value)
 * @method static \Staudenmeir\LaravelAdjacencyList\Eloquent\Builder<static>|ImprestAccount whereTag($value)
 * @method static \Staudenmeir\LaravelAdjacencyList\Eloquent\Builder<static>|ImprestAccount whereUpdatedAt($value)
 * @method static \Staudenmeir\LaravelAdjacencyList\Eloquent\Builder<static>|ImprestAccount withCode()
 * @method static \Staudenmeir\LaravelAdjacencyList\Eloquent\Builder<static>|ImprestAccount withGlobalScopes(array $scopes)
 * @method static \Staudenmeir\LaravelAdjacencyList\Eloquent\Builder<static>|ImprestAccount withRelationshipExpression($direction, callable $constraint, $initialDepth, $from = null, $maxDepth = null)
 * @mixin \Eloquent
 */
class ImprestAccount extends Account
{
    use HasFactory;

    public function member()
    {
        return $this->belongsTo(Member::class);
    }

    protected static function boot()
    {
        parent::boot();
        static::addGlobalScope('member_imprest_savings', function ($query) {
            $query->whereNotNull('member_id')->whereTag('imprest_savings');
        });
    }
}
