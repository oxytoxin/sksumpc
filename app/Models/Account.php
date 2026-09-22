<?php

namespace App\Models;

use App\Enums\TransactionTypes;
use App\Oxytoxin\Traits\CreatesChildren;
use Carbon\CarbonImmutable;
use DB;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Staudenmeir\LaravelAdjacencyList\Eloquent\Collection;
use Staudenmeir\LaravelAdjacencyList\Eloquent\HasRecursiveRelationships;

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
 * @property CarbonImmutable|null $created_at
 * @property CarbonImmutable|null $updated_at
 * @property int $show_sum
 * @property string $sum_description
 * @property int $sort
 * @property-read AccountType $account_type
 * @property-read Collection<int, Account> $children
 * @property-read int|null $children_count
 * @property-read Member|null $member
 * @property-read Account|null $parent
 * @property-read \Illuminate\Database\Eloquent\Collection<int, Transaction> $transactions
 * @property-read int|null $transactions_count
 * @property-read int $depth
 * @property-read string $path
 * @property-read string $fullname
 * @property-read Collection<int, Account> $ancestors The model's recursive parents.
 * @property-read int|null $ancestors_count
 * @property-read Collection<int, Account> $ancestorsAndSelf The model's recursive parents and itself.
 * @property-read int|null $ancestors_and_self_count
 * @property-read Collection<int, Account> $bloodline The model's ancestors, descendants and itself.
 * @property-read int|null $bloodline_count
 * @property-read Collection<int, Account> $childrenAndSelf The model's direct children and itself.
 * @property-read int|null $children_and_self_count
 * @property-read Collection<int, Account> $descendants The model's recursive children.
 * @property-read int|null $descendants_count
 * @property-read Collection<int, Account> $descendantsAndSelf The model's recursive children and itself.
 * @property-read int|null $descendants_and_self_count
 * @property-read Collection<int, Account> $parentAndSelf The model's direct parent and itself.
 * @property-read int|null $parent_and_self_count
 * @property-read Account|null $rootAncestor The model's topmost parent.
 * @property-read Collection<int, Account> $siblings The parent's other children.
 * @property-read int|null $siblings_count
 * @property-read Collection<int, Account> $siblingsAndSelf All the parent's children.
 * @property-read int|null $siblings_and_self_count
 *
 * @method static \Staudenmeir\LaravelAdjacencyList\Eloquent\Collection<int, static> all($columns = ['*'])
 * @method static \Staudenmeir\LaravelAdjacencyList\Eloquent\Builder<static>|Account breadthFirst()
 * @method static \Staudenmeir\LaravelAdjacencyList\Eloquent\Builder<static>|Account depthFirst()
 * @method static \Staudenmeir\LaravelAdjacencyList\Eloquent\Builder<static>|Account doesntHaveChildren()
 * @method static \Staudenmeir\LaravelAdjacencyList\Eloquent\Collection<int, static> get($columns = ['*'])
 * @method static \Staudenmeir\LaravelAdjacencyList\Eloquent\Builder<static>|Account getExpressionGrammar()
 * @method static \Staudenmeir\LaravelAdjacencyList\Eloquent\Builder<static>|Account hasChildren()
 * @method static \Staudenmeir\LaravelAdjacencyList\Eloquent\Builder<static>|Account hasParent()
 * @method static \Staudenmeir\LaravelAdjacencyList\Eloquent\Builder<static>|Account isLeaf()
 * @method static \Staudenmeir\LaravelAdjacencyList\Eloquent\Builder<static>|Account isRoot()
 * @method static \Staudenmeir\LaravelAdjacencyList\Eloquent\Builder<static>|Account newModelQuery()
 * @method static \Staudenmeir\LaravelAdjacencyList\Eloquent\Builder<static>|Account newQuery()
 * @method static \Staudenmeir\LaravelAdjacencyList\Eloquent\Builder<static>|Account query()
 * @method static \Staudenmeir\LaravelAdjacencyList\Eloquent\Builder<static>|Account tree($maxDepth = null)
 * @method static \Staudenmeir\LaravelAdjacencyList\Eloquent\Builder<static>|Account treeOf(\Illuminate\Database\Eloquent\Model|callable $constraint, $maxDepth = null)
 * @method static \Staudenmeir\LaravelAdjacencyList\Eloquent\Builder<static>|Account whereAccountTypeId($value)
 * @method static \Staudenmeir\LaravelAdjacencyList\Eloquent\Builder<static>|Account whereAccountableId($value)
 * @method static \Staudenmeir\LaravelAdjacencyList\Eloquent\Builder<static>|Account whereAccountableType($value)
 * @method static \Staudenmeir\LaravelAdjacencyList\Eloquent\Builder<static>|Account whereCreatedAt($value)
 * @method static \Staudenmeir\LaravelAdjacencyList\Eloquent\Builder<static>|Account whereDepth($operator, $value = null)
 * @method static \Staudenmeir\LaravelAdjacencyList\Eloquent\Builder<static>|Account whereId($value)
 * @method static \Staudenmeir\LaravelAdjacencyList\Eloquent\Builder<static>|Account whereMemberId($value)
 * @method static \Staudenmeir\LaravelAdjacencyList\Eloquent\Builder<static>|Account whereName($value)
 * @method static \Staudenmeir\LaravelAdjacencyList\Eloquent\Builder<static>|Account whereNumber($value)
 * @method static \Staudenmeir\LaravelAdjacencyList\Eloquent\Builder<static>|Account whereParentId($value)
 * @method static \Staudenmeir\LaravelAdjacencyList\Eloquent\Builder<static>|Account whereShowSum($value)
 * @method static \Staudenmeir\LaravelAdjacencyList\Eloquent\Builder<static>|Account whereSort($value)
 * @method static \Staudenmeir\LaravelAdjacencyList\Eloquent\Builder<static>|Account whereSumDescription($value)
 * @method static \Staudenmeir\LaravelAdjacencyList\Eloquent\Builder<static>|Account whereTag($value)
 * @method static \Staudenmeir\LaravelAdjacencyList\Eloquent\Builder<static>|Account whereUpdatedAt($value)
 * @method static \Staudenmeir\LaravelAdjacencyList\Eloquent\Builder<static>|Account withCode()
 * @method static \Staudenmeir\LaravelAdjacencyList\Eloquent\Builder<static>|Account withGlobalScopes(array $scopes)
 * @method static \Staudenmeir\LaravelAdjacencyList\Eloquent\Builder<static>|Account withRelationshipExpression($direction, callable $constraint, $initialDepth, $from = null, $maxDepth = null)
 *
 * @mixin \Eloquent
 */
class Account extends Model
{
    use CreatesChildren, HasFactory, HasRecursiveRelationships;

    protected $table = 'accounts';

    protected $casts = [
        'closed_at' => 'immutable_datetime',
    ];

    public function getCustomPaths()
    {
        return [
            [
                'name' => 'fullname',
                'column' => 'name',
                'separator' => ':',
            ],
        ];
    }

    public function member(): BelongsTo
    {
        return $this->belongsTo(Member::class);
    }

    public static function getCashOnHand()
    {
        return Account::firstWhere('tag', 'cash_on_hand');
    }

    public static function getCashInBankGF()
    {
        return Account::firstWhere('tag', 'cash_in_bank_dbp_gf');
    }

    public static function getCashInBankMSO()
    {
        return Account::firstWhere('tag', 'cash_in_bank_dbp_mso');
    }

    public static function getRevolvingFund()
    {
        return Account::firstWhere('tag', 'revolving_fund');
    }

    public static function getTimeDepositInterestExpense()
    {
        return Account::firstWhere('tag', 'time_deposit_interest_expense');
    }

    public static function getSavingsInterestExpense()
    {
        return Account::firstWhere('tag', 'savings_deposit_interest_expense');
    }

    public static function getMemberTimeDeposits()
    {
        return Account::firstWhere('tag', 'member_time_deposits');
    }

    public static function getLoanReceivable(LoanType $loanType)
    {
        return Account::whereAccountableType(LoanType::class)->whereAccountableId($loanType->id)->whereTag('loan_receivables')->first();
    }

    public static function getFamilyInsurance()
    {
        return Account::firstWhere('tag', 'family_insurance');
    }

    public static function getServiceFeeLoans()
    {
        return Account::firstWhere('tag', 'service_fee_loans');
    }

    public static function getLoanInsurance()
    {
        return Account::firstWhere('tag', 'insurance_loans');
    }

    public static function getFinesPenaltiesSurcharges(): self
    {
        return Account::firstWhere('tag', 'fines_penalties_surcharges')
            ?? Account::where('number', '40140')->firstOrFail();
    }

    public static function getCbuDeposit($member_type_id)
    {
        return match ($member_type_id) {
            1 => Account::firstWhere('tag', 'member_regular_cbu_deposit'),
            2 => Account::firstWhere('tag', 'member_preferred_cbu_deposit'),
            3 => Account::firstWhere('tag', 'member_laboratory_cbu_deposit'),
            default => Account::firstWhere('tag', 'member_regular_cbu_deposit'),
        };
    }

    public function scopeWithCode(Builder $query)
    {
        return $query->tree()->orderBy('id')->addSelect(DB::raw("*,concat(number,' - ', fullname) as code"));
    }

    public function account_type()
    {
        return $this->belongsTo(AccountType::class);
    }

    public function transactions()
    {
        return $this->hasMany(Transaction::class, 'account_id', 'id');
    }

    public function scopeActive(Builder $query): Builder
    {
        return $query->whereNull('closed_at');
    }

    public function scopeClosed(Builder $query): Builder
    {
        return $query->whereNotNull('closed_at');
    }

    public function isClosed(): bool
    {
        return $this->closed_at !== null;
    }

    public function isActive(): bool
    {
        return ! $this->isClosed();
    }

    public function close(?string $remarks = null): void
    {
        $this->update([
            'closed_at' => config('app.transaction_date') ?? today(),
            'close_remarks' => $remarks,
        ]);
    }

    public function recursiveTransactions()
    {
        return $this->hasManyOfDescendantsAndSelf(Transaction::class);
    }

    public function recursiveCrjTransactions()
    {
        return $this->hasManyOfDescendantsAndSelf(Transaction::class)->where('transaction_type_id', TransactionTypes::CRJ->value);
    }

    public function recursiveCdjTransactions()
    {
        return $this->hasManyOfDescendantsAndSelf(Transaction::class)->where('transaction_type_id', TransactionTypes::CDJ->value);
    }

    public function recursiveJevTransactions()
    {
        return $this->hasManyOfDescendantsAndSelf(Transaction::class)->where('transaction_type_id', TransactionTypes::JEV->value);
    }
}
