<?php

// @formatter:off
// phpcs:ignoreFile
/**
 * A helper file for your Eloquent Models
 * Copy the phpDocs from this file to the correct Model,
 * And remove them from this file, to prevent double declarations.
 *
 * @author Barry vd. Heuvel <barryvdh@gmail.com>
 */


namespace App\Models{
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
 * @property-read \App\Models\AccountType $account_type
 * @property-read \Staudenmeir\LaravelAdjacencyList\Eloquent\Collection<int, \App\Models\Account> $children
 * @property-read int|null $children_count
 * @property-read \App\Models\Member|null $member
 * @property-read \App\Models\Account|null $parent
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Transaction> $transactions
 * @property-read int|null $transactions_count
 * @property-read int $depth
 * @property-read string $path
 * @property-read string $fullname
 * @property-read \Staudenmeir\LaravelAdjacencyList\Eloquent\Collection<int, \App\Models\Account> $ancestors The model's recursive parents.
 * @property-read int|null $ancestors_count
 * @property-read \Staudenmeir\LaravelAdjacencyList\Eloquent\Collection<int, \App\Models\Account> $ancestorsAndSelf The model's recursive parents and itself.
 * @property-read int|null $ancestors_and_self_count
 * @property-read \Staudenmeir\LaravelAdjacencyList\Eloquent\Collection<int, \App\Models\Account> $bloodline The model's ancestors, descendants and itself.
 * @property-read int|null $bloodline_count
 * @property-read \Staudenmeir\LaravelAdjacencyList\Eloquent\Collection<int, \App\Models\Account> $childrenAndSelf The model's direct children and itself.
 * @property-read int|null $children_and_self_count
 * @property-read \Staudenmeir\LaravelAdjacencyList\Eloquent\Collection<int, \App\Models\Account> $descendants The model's recursive children.
 * @property-read int|null $descendants_count
 * @property-read \Staudenmeir\LaravelAdjacencyList\Eloquent\Collection<int, \App\Models\Account> $descendantsAndSelf The model's recursive children and itself.
 * @property-read int|null $descendants_and_self_count
 * @property-read \Staudenmeir\LaravelAdjacencyList\Eloquent\Collection<int, \App\Models\Account> $parentAndSelf The model's direct parent and itself.
 * @property-read int|null $parent_and_self_count
 * @property-read \App\Models\Account|null $rootAncestor The model's topmost parent.
 * @property-read \Staudenmeir\LaravelAdjacencyList\Eloquent\Collection<int, \App\Models\Account> $siblings The parent's other children.
 * @property-read int|null $siblings_count
 * @property-read \Staudenmeir\LaravelAdjacencyList\Eloquent\Collection<int, \App\Models\Account> $siblingsAndSelf All the parent's children.
 * @property-read int|null $siblings_and_self_count
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
 * @mixin \Eloquent
 */
	#[\AllowDynamicProperties]
	class IdeHelperAccount {}
}

namespace App\Models{
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
	#[\AllowDynamicProperties]
	class IdeHelperAccountType {}
}

namespace App\Models{
/**
 * @property int $id
 * @property int $balance_forwarded_summary_id
 * @property int $account_id
 * @property numeric|null $credit
 * @property numeric|null $debit
 * @property \Carbon\CarbonImmutable|null $created_at
 * @property \Carbon\CarbonImmutable|null $updated_at
 * @property-read \App\Models\Account $account
 * @property-read \App\Models\BalanceForwardedSummary $balance_forwarded_summary
 * @method static \Illuminate\Database\Eloquent\Builder<static>|BalanceForwardedEntry newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|BalanceForwardedEntry newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|BalanceForwardedEntry query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|BalanceForwardedEntry whereAccountId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|BalanceForwardedEntry whereBalanceForwardedSummaryId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|BalanceForwardedEntry whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|BalanceForwardedEntry whereCredit($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|BalanceForwardedEntry whereDebit($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|BalanceForwardedEntry whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|BalanceForwardedEntry whereUpdatedAt($value)
 * @mixin \Eloquent
 */
	#[\AllowDynamicProperties]
	class IdeHelperBalanceForwardedEntry {}
}

namespace App\Models{
/**
 * @property int $id
 * @property \Carbon\CarbonImmutable $generated_date
 * @property \Carbon\CarbonImmutable|null $created_at
 * @property \Carbon\CarbonImmutable|null $updated_at
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\BalanceForwardedEntry> $balance_forwarded_entries
 * @property-read int|null $balance_forwarded_entries_count
 * @method static \Illuminate\Database\Eloquent\Builder<static>|BalanceForwardedSummary newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|BalanceForwardedSummary newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|BalanceForwardedSummary query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|BalanceForwardedSummary whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|BalanceForwardedSummary whereGeneratedDate($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|BalanceForwardedSummary whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|BalanceForwardedSummary whereUpdatedAt($value)
 * @mixin \Eloquent
 */
	#[\AllowDynamicProperties]
	class IdeHelperBalanceForwardedSummary {}
}

namespace App\Models{
/**
 * @property int $id
 * @property int $municipality_id
 * @property string $name
 * @property-read \App\Models\Municipality $municipality
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Barangay newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Barangay newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Barangay query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Barangay whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Barangay whereMunicipalityId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Barangay whereName($value)
 * @mixin \Eloquent
 */
	#[\AllowDynamicProperties]
	class IdeHelperBarangay {}
}

namespace App\Models{
/**
 * @property int $id
 * @property int $member_id
 * @property bool $is_active
 * @property string|null $code
 * @property int $number_of_terms
 * @property numeric $number_of_shares
 * @property numeric $amount_subscribed
 * @property numeric|null $monthly_payment
 * @property numeric|null $initial_amount_paid
 * @property numeric $par_value
 * @property numeric $actual_amount_paid
 * @property numeric|null $total_amount_paid
 * @property numeric $outstanding_balance
 * @property \Carbon\CarbonImmutable $transaction_date
 * @property \Carbon\CarbonImmutable|null $created_at
 * @property \Carbon\CarbonImmutable|null $updated_at
 * @property-read \App\Models\CapitalSubscriptionAmortization|null $active_capital_subscription_amortization
 * @property-read mixed $amount_shares_subscribed
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\CapitalSubscriptionAmortization> $capital_subscription_amortizations
 * @property-read int|null $capital_subscription_amortizations_count
 * @property-read \App\Models\Member $member
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\CapitalSubscriptionAmortization> $paid_capital_subscription_amortizations
 * @property-read int|null $paid_capital_subscription_amortizations_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\CapitalSubscriptionPayment> $payments
 * @property-read int|null $payments_count
 * @method static \Illuminate\Database\Eloquent\Builder<static>|CapitalSubscription newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|CapitalSubscription newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|CapitalSubscription query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|CapitalSubscription whereActualAmountPaid($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|CapitalSubscription whereAmountSubscribed($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|CapitalSubscription whereCode($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|CapitalSubscription whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|CapitalSubscription whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|CapitalSubscription whereInitialAmountPaid($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|CapitalSubscription whereIsActive($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|CapitalSubscription whereMemberId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|CapitalSubscription whereMonthlyPayment($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|CapitalSubscription whereNumberOfShares($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|CapitalSubscription whereNumberOfTerms($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|CapitalSubscription whereOutstandingBalance($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|CapitalSubscription whereParValue($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|CapitalSubscription whereTotalAmountPaid($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|CapitalSubscription whereTransactionDate($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|CapitalSubscription whereUpdatedAt($value)
 * @mixin \Eloquent
 */
	#[\AllowDynamicProperties]
	class IdeHelperCapitalSubscription {}
}

namespace App\Models{
/**
 * @property-read \App\Models\CapitalSubscription|null $capital_subscription
 * @method static \Illuminate\Database\Eloquent\Builder<static>|CapitalSubscriptionAmortization newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|CapitalSubscriptionAmortization newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|CapitalSubscriptionAmortization query()
 * @mixin \Eloquent
 */
	#[\AllowDynamicProperties]
	class IdeHelperCapitalSubscriptionAmortization {}
}

namespace App\Models{
/**
 * @property int $id
 * @property \Carbon\CarbonImmutable $date
 * @property string|null $billable_date
 * @property int|null $payment_type_id
 * @property int|null $member_type_id
 * @property int|null $member_subtype_id
 * @property string|null $reference_number
 * @property string|null $name
 * @property string|null $or_number
 * @property \Carbon\CarbonImmutable|null $or_date
 * @property int|null $cashier_id
 * @property bool $posted
 * @property bool $for_or
 * @property \Carbon\CarbonImmutable|null $created_at
 * @property \Carbon\CarbonImmutable|null $updated_at
 * @property-read mixed $or_approved
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\CapitalSubscriptionBillingPayment> $capital_subscription_billing_payments
 * @property-read int|null $capital_subscription_billing_payments_count
 * @property-read \App\Models\User|null $cashier
 * @property-read \App\Models\PaymentType|null $payment_type
 * @method static \Illuminate\Database\Eloquent\Builder<static>|CapitalSubscriptionBilling newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|CapitalSubscriptionBilling newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|CapitalSubscriptionBilling query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|CapitalSubscriptionBilling whereBillableDate($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|CapitalSubscriptionBilling whereCashierId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|CapitalSubscriptionBilling whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|CapitalSubscriptionBilling whereDate($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|CapitalSubscriptionBilling whereForOr($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|CapitalSubscriptionBilling whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|CapitalSubscriptionBilling whereMemberSubtypeId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|CapitalSubscriptionBilling whereMemberTypeId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|CapitalSubscriptionBilling whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|CapitalSubscriptionBilling whereOrDate($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|CapitalSubscriptionBilling whereOrNumber($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|CapitalSubscriptionBilling wherePaymentTypeId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|CapitalSubscriptionBilling wherePosted($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|CapitalSubscriptionBilling whereReferenceNumber($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|CapitalSubscriptionBilling whereUpdatedAt($value)
 * @mixin \Eloquent
 */
	#[\AllowDynamicProperties]
	class IdeHelperCapitalSubscriptionBilling {}
}

namespace App\Models{
/**
 * @property int $id
 * @property int $capital_subscription_billing_id
 * @property int $capital_subscription_id
 * @property int $member_id
 * @property numeric $amount_due
 * @property numeric $amount_paid
 * @property int $posted
 * @property \Carbon\CarbonImmutable|null $created_at
 * @property \Carbon\CarbonImmutable|null $updated_at
 * @property-read \App\Models\CapitalSubscription $capital_subscription
 * @property-read \App\Models\CapitalSubscriptionBilling $capital_subscription_billing
 * @property-read \App\Models\Member $member
 * @method static \Illuminate\Database\Eloquent\Builder<static>|CapitalSubscriptionBillingPayment newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|CapitalSubscriptionBillingPayment newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|CapitalSubscriptionBillingPayment query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|CapitalSubscriptionBillingPayment whereAmountDue($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|CapitalSubscriptionBillingPayment whereAmountPaid($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|CapitalSubscriptionBillingPayment whereCapitalSubscriptionBillingId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|CapitalSubscriptionBillingPayment whereCapitalSubscriptionId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|CapitalSubscriptionBillingPayment whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|CapitalSubscriptionBillingPayment whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|CapitalSubscriptionBillingPayment whereMemberId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|CapitalSubscriptionBillingPayment wherePosted($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|CapitalSubscriptionBillingPayment whereUpdatedAt($value)
 * @mixin \Eloquent
 */
	#[\AllowDynamicProperties]
	class IdeHelperCapitalSubscriptionBillingPayment {}
}

namespace App\Models{
/**
 * @property int $id
 * @property int $capital_subscription_id
 * @property int $member_id
 * @property int $payment_type_id
 * @property numeric $amount
 * @property numeric|null $deposit
 * @property numeric|null $withdrawal
 * @property numeric $running_balance
 * @property string $reference_number
 * @property string|null $remarks
 * @property \Carbon\CarbonImmutable $transaction_date
 * @property int|null $cashier_id
 * @property \Carbon\CarbonImmutable|null $created_at
 * @property \Carbon\CarbonImmutable|null $updated_at
 * @property-read \App\Models\CapitalSubscription $capital_subscription
 * @property-read \App\Models\User|null $cashier
 * @property-read \App\Models\Member $member
 * @method static \Illuminate\Database\Eloquent\Builder<static>|CapitalSubscriptionPayment newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|CapitalSubscriptionPayment newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|CapitalSubscriptionPayment query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|CapitalSubscriptionPayment whereAmount($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|CapitalSubscriptionPayment whereCapitalSubscriptionId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|CapitalSubscriptionPayment whereCashierId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|CapitalSubscriptionPayment whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|CapitalSubscriptionPayment whereDeposit($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|CapitalSubscriptionPayment whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|CapitalSubscriptionPayment whereMemberId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|CapitalSubscriptionPayment wherePaymentTypeId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|CapitalSubscriptionPayment whereReferenceNumber($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|CapitalSubscriptionPayment whereRemarks($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|CapitalSubscriptionPayment whereRunningBalance($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|CapitalSubscriptionPayment whereTransactionDate($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|CapitalSubscriptionPayment whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|CapitalSubscriptionPayment whereWithdrawal($value)
 * @mixin \Eloquent
 */
	#[\AllowDynamicProperties]
	class IdeHelperCapitalSubscriptionPayment {}
}

namespace App\Models{
/**
 * @property int $id
 * @property numeric $amount
 * @property \Carbon\CarbonImmutable $transaction_date
 * @property int|null $cashier_id
 * @property \Carbon\CarbonImmutable|null $created_at
 * @property \Carbon\CarbonImmutable|null $updated_at
 * @property-read \App\Models\User|null $cashier
 * @method static \Illuminate\Database\Eloquent\Builder<static>|CashBeginning newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|CashBeginning newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|CashBeginning query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|CashBeginning whereAmount($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|CashBeginning whereCashierId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|CashBeginning whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|CashBeginning whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|CashBeginning whereTransactionDate($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|CashBeginning whereUpdatedAt($value)
 * @mixin \Eloquent
 */
	#[\AllowDynamicProperties]
	class IdeHelperCashBeginning {}
}

namespace App\Models{
/**
 * @property int $id
 * @property int $cash_collectible_category_id
 * @property string $name
 * @property bool $has_inventory
 * @property \Carbon\CarbonImmutable|null $created_at
 * @property \Carbon\CarbonImmutable|null $updated_at
 * @property-read \App\Models\CashCollectibleCategory $cash_collectible_category
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\CashCollectiblePayment> $payments
 * @property-read int|null $payments_count
 * @method static \Illuminate\Database\Eloquent\Builder<static>|CashCollectible newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|CashCollectible newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|CashCollectible query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|CashCollectible whereCashCollectibleCategoryId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|CashCollectible whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|CashCollectible whereHasInventory($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|CashCollectible whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|CashCollectible whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|CashCollectible whereUpdatedAt($value)
 * @mixin \Eloquent
 */
	#[\AllowDynamicProperties]
	class IdeHelperCashCollectible {}
}

namespace App\Models{
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
 * @property-read \App\Models\AccountType $account_type
 * @property-read \Staudenmeir\LaravelAdjacencyList\Eloquent\Collection<int, \App\Models\CashCollectibleAccount> $children
 * @property-read int|null $children_count
 * @property-read \App\Models\Member|null $member
 * @property-read \App\Models\CashCollectibleAccount|null $parent
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Transaction> $transactions
 * @property-read int|null $transactions_count
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
	#[\AllowDynamicProperties]
	class IdeHelperCashCollectibleAccount {}
}

namespace App\Models{
/**
 * @property int $id
 * @property \Carbon\CarbonImmutable $date
 * @property int $account_id
 * @property string|null $billable_date
 * @property int|null $payment_type_id
 * @property string|null $reference_number
 * @property string|null $name
 * @property string|null $or_number
 * @property \Carbon\CarbonImmutable|null $or_date
 * @property int|null $cashier_id
 * @property bool $posted
 * @property bool $for_or
 * @property \Carbon\CarbonImmutable|null $created_at
 * @property \Carbon\CarbonImmutable|null $updated_at
 * @property-read mixed $or_approved
 * @property-read \App\Models\CashCollectibleAccount $cash_collectible_account
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\CashCollectibleBillingPayment> $cash_collectible_billing_payments
 * @property-read int|null $cash_collectible_billing_payments_count
 * @property-read \App\Models\User|null $cashier
 * @property-read \App\Models\PaymentType|null $payment_type
 * @method static \Illuminate\Database\Eloquent\Builder<static>|CashCollectibleBilling newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|CashCollectibleBilling newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|CashCollectibleBilling query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|CashCollectibleBilling whereAccountId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|CashCollectibleBilling whereBillableDate($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|CashCollectibleBilling whereCashierId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|CashCollectibleBilling whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|CashCollectibleBilling whereDate($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|CashCollectibleBilling whereForOr($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|CashCollectibleBilling whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|CashCollectibleBilling whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|CashCollectibleBilling whereOrDate($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|CashCollectibleBilling whereOrNumber($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|CashCollectibleBilling wherePaymentTypeId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|CashCollectibleBilling wherePosted($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|CashCollectibleBilling whereReferenceNumber($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|CashCollectibleBilling whereUpdatedAt($value)
 * @mixin \Eloquent
 */
	#[\AllowDynamicProperties]
	class IdeHelperCashCollectibleBilling {}
}

namespace App\Models{
/**
 * @property int $id
 * @property int $cash_collectible_billing_id
 * @property int $account_id
 * @property int|null $member_id
 * @property string $payee
 * @property numeric $amount_due
 * @property numeric $amount_paid
 * @property int $posted
 * @property \Carbon\CarbonImmutable|null $created_at
 * @property \Carbon\CarbonImmutable|null $updated_at
 * @property-read \App\Models\CashCollectibleAccount $cash_collectible_account
 * @property-read \App\Models\CashCollectibleBilling $cash_collectible_billing
 * @property-read \App\Models\Member|null $member
 * @method static \Illuminate\Database\Eloquent\Builder<static>|CashCollectibleBillingPayment newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|CashCollectibleBillingPayment newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|CashCollectibleBillingPayment query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|CashCollectibleBillingPayment whereAccountId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|CashCollectibleBillingPayment whereAmountDue($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|CashCollectibleBillingPayment whereAmountPaid($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|CashCollectibleBillingPayment whereCashCollectibleBillingId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|CashCollectibleBillingPayment whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|CashCollectibleBillingPayment whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|CashCollectibleBillingPayment whereMemberId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|CashCollectibleBillingPayment wherePayee($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|CashCollectibleBillingPayment wherePosted($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|CashCollectibleBillingPayment whereUpdatedAt($value)
 * @mixin \Eloquent
 */
	#[\AllowDynamicProperties]
	class IdeHelperCashCollectibleBillingPayment {}
}

namespace App\Models{
/**
 * @property int $id
 * @property string $name
 * @property \Carbon\CarbonImmutable|null $created_at
 * @property \Carbon\CarbonImmutable|null $updated_at
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\CashCollectible> $cash_collectibles
 * @property-read int|null $cash_collectibles_count
 * @method static \Illuminate\Database\Eloquent\Builder<static>|CashCollectibleCategory newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|CashCollectibleCategory newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|CashCollectibleCategory query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|CashCollectibleCategory whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|CashCollectibleCategory whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|CashCollectibleCategory whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|CashCollectibleCategory whereUpdatedAt($value)
 * @mixin \Eloquent
 */
	#[\AllowDynamicProperties]
	class IdeHelperCashCollectibleCategory {}
}

namespace App\Models{
/**
 * @property int $id
 * @property int $cash_collectible_id
 * @property int|null $member_id
 * @property string $payee
 * @property int $payment_type_id
 * @property string $reference_number
 * @property numeric $amount
 * @property \Carbon\CarbonImmutable $transaction_date
 * @property int|null $cashier_id
 * @property \Carbon\CarbonImmutable|null $created_at
 * @property \Carbon\CarbonImmutable|null $updated_at
 * @property-read \App\Models\CashCollectible $cash_collectible
 * @property-read \App\Models\User|null $cashier
 * @property-read \App\Models\Member|null $member
 * @method static \Illuminate\Database\Eloquent\Builder<static>|CashCollectiblePayment newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|CashCollectiblePayment newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|CashCollectiblePayment query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|CashCollectiblePayment whereAmount($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|CashCollectiblePayment whereCashCollectibleId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|CashCollectiblePayment whereCashierId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|CashCollectiblePayment whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|CashCollectiblePayment whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|CashCollectiblePayment whereMemberId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|CashCollectiblePayment wherePayee($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|CashCollectiblePayment wherePaymentTypeId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|CashCollectiblePayment whereReferenceNumber($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|CashCollectiblePayment whereTransactionDate($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|CashCollectiblePayment whereUpdatedAt($value)
 * @mixin \Eloquent
 */
	#[\AllowDynamicProperties]
	class IdeHelperCashCollectiblePayment {}
}

namespace App\Models{
/**
 * @property int $id
 * @property int $account_id
 * @property int|null $member_id
 * @property string $payee
 * @property numeric $amount
 * @property int $number_of_terms
 * @property numeric|null $billable_amount
 * @property \Carbon\CarbonImmutable|null $created_at
 * @property \Carbon\CarbonImmutable|null $updated_at
 * @property-read \App\Models\CashCollectibleAccount $cash_collectible_account
 * @property-read \App\Models\Member|null $member
 * @method static \Illuminate\Database\Eloquent\Builder<static>|CashCollectibleSubscription newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|CashCollectibleSubscription newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|CashCollectibleSubscription query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|CashCollectibleSubscription whereAccountId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|CashCollectibleSubscription whereAmount($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|CashCollectibleSubscription whereBillableAmount($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|CashCollectibleSubscription whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|CashCollectibleSubscription whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|CashCollectibleSubscription whereMemberId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|CashCollectibleSubscription whereNumberOfTerms($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|CashCollectibleSubscription wherePayee($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|CashCollectibleSubscription whereUpdatedAt($value)
 * @mixin \Eloquent
 */
	#[\AllowDynamicProperties]
	class IdeHelperCashCollectibleSubscription {}
}

namespace App\Models{
/**
 * @property int $id
 * @property string $name
 * @property \Carbon\CarbonImmutable|null $created_at
 * @property \Carbon\CarbonImmutable|null $updated_at
 * @method static \Illuminate\Database\Eloquent\Builder<static>|CivilStatus newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|CivilStatus newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|CivilStatus query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|CivilStatus whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|CivilStatus whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|CivilStatus whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|CivilStatus whereUpdatedAt($value)
 * @mixin \Eloquent
 */
	#[\AllowDynamicProperties]
	class IdeHelperCivilStatus {}
}

namespace App\Models{
/**
 * @property int $id
 * @property int $loan_application_id
 * @property array<array-key, mixed> $details
 * @property \Carbon\CarbonImmutable|null $created_at
 * @property \Carbon\CarbonImmutable|null $updated_at
 * @property-read \App\Models\LoanApplication $loan_application
 * @method static \Illuminate\Database\Eloquent\Builder<static>|CreditAndBackgroundInvestigation newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|CreditAndBackgroundInvestigation newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|CreditAndBackgroundInvestigation query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|CreditAndBackgroundInvestigation whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|CreditAndBackgroundInvestigation whereDetails($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|CreditAndBackgroundInvestigation whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|CreditAndBackgroundInvestigation whereLoanApplicationId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|CreditAndBackgroundInvestigation whereUpdatedAt($value)
 * @mixin \Eloquent
 */
	#[\AllowDynamicProperties]
	class IdeHelperCreditAndBackgroundInvestigation {}
}

namespace App\Models{
/**
 * @property int $id
 * @property string $name
 * @property \Carbon\CarbonImmutable|null $created_at
 * @property \Carbon\CarbonImmutable|null $updated_at
 * @method static \Illuminate\Database\Eloquent\Builder<static>|DisapprovalReason newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|DisapprovalReason newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|DisapprovalReason query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|DisapprovalReason whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|DisapprovalReason whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|DisapprovalReason whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|DisapprovalReason whereUpdatedAt($value)
 * @mixin \Eloquent
 */
	#[\AllowDynamicProperties]
	class IdeHelperDisapprovalReason {}
}

namespace App\Models{
/**
 * @property int $id
 * @property int $voucher_type_id
 * @property string $name
 * @property string|null $address
 * @property string|null $check_number
 * @property string $reference_number
 * @property string $voucher_number
 * @property string $description
 * @property \Carbon\CarbonImmutable $transaction_date
 * @property int $bookkeeper_id
 * @property int $is_legacy
 * @property \Carbon\CarbonImmutable|null $created_at
 * @property \Carbon\CarbonImmutable|null $updated_at
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\DisbursementVoucherItem> $disbursement_voucher_items
 * @property-read int|null $disbursement_voucher_items_count
 * @property-read \App\Models\VoucherType $voucher_type
 * @method static \Illuminate\Database\Eloquent\Builder<static>|DisbursementVoucher newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|DisbursementVoucher newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|DisbursementVoucher query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|DisbursementVoucher whereAddress($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|DisbursementVoucher whereBookkeeperId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|DisbursementVoucher whereCheckNumber($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|DisbursementVoucher whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|DisbursementVoucher whereDescription($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|DisbursementVoucher whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|DisbursementVoucher whereIsLegacy($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|DisbursementVoucher whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|DisbursementVoucher whereReferenceNumber($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|DisbursementVoucher whereTransactionDate($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|DisbursementVoucher whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|DisbursementVoucher whereVoucherNumber($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|DisbursementVoucher whereVoucherTypeId($value)
 * @mixin \Eloquent
 */
	#[\AllowDynamicProperties]
	class IdeHelperDisbursementVoucher {}
}

namespace App\Models{
/**
 * @property int $id
 * @property int $disbursement_voucher_id
 * @property int $account_id
 * @property numeric|null $credit
 * @property numeric|null $debit
 * @property string $transaction_date
 * @property array<array-key, mixed> $details
 * @property \Carbon\CarbonImmutable|null $created_at
 * @property \Carbon\CarbonImmutable|null $updated_at
 * @property-read \App\Models\Account $account
 * @property-read \App\Models\DisbursementVoucher $disbursement_voucher
 * @property-read \App\Models\Account $item_account
 * @method static \Illuminate\Database\Eloquent\Builder<static>|DisbursementVoucherItem newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|DisbursementVoucherItem newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|DisbursementVoucherItem query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|DisbursementVoucherItem whereAccountId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|DisbursementVoucherItem whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|DisbursementVoucherItem whereCredit($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|DisbursementVoucherItem whereDebit($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|DisbursementVoucherItem whereDetails($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|DisbursementVoucherItem whereDisbursementVoucherId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|DisbursementVoucherItem whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|DisbursementVoucherItem whereTransactionDate($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|DisbursementVoucherItem whereUpdatedAt($value)
 * @mixin \Eloquent
 */
	#[\AllowDynamicProperties]
	class IdeHelperDisbursementVoucherItem {}
}

namespace App\Models{
/**
 * @property int $id
 * @property string $name
 * @property \Carbon\CarbonImmutable|null $created_at
 * @property \Carbon\CarbonImmutable|null $updated_at
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Member> $members
 * @property-read int|null $members_count
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Division newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Division newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Division query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Division whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Division whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Division whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Division whereUpdatedAt($value)
 * @mixin \Eloquent
 */
	#[\AllowDynamicProperties]
	class IdeHelperDivision {}
}

namespace App\Models{
/**
 * @property int $id
 * @property string $name
 * @property \Carbon\CarbonImmutable|null $created_at
 * @property \Carbon\CarbonImmutable|null $updated_at
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Gender newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Gender newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Gender query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Gender whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Gender whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Gender whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Gender whereUpdatedAt($value)
 * @mixin \Eloquent
 */
	#[\AllowDynamicProperties]
	class IdeHelperGender {}
}

namespace App\Models{
/**
 * @property int $id
 * @property int $member_id
 * @property int $payment_type_id
 * @property string $reference_number
 * @property numeric $amount
 * @property numeric|null $deposit
 * @property numeric|null $withdrawal
 * @property numeric $interest_rate
 * @property numeric $interest
 * @property \Carbon\CarbonImmutable $transaction_date
 * @property \Carbon\CarbonImmutable|null $transaction_datetime
 * @property \Carbon\CarbonImmutable|null $interest_date
 * @property numeric $balance
 * @property bool $accrued
 * @property int|null $cashier_id
 * @property \Carbon\CarbonImmutable|null $created_at
 * @property \Carbon\CarbonImmutable|null $updated_at
 * @property-read \App\Models\Account|null $account
 * @property-read \App\Models\User|null $cashier
 * @property-read \App\Models\Member $member
 * @property-read \App\Models\RevolvingFund|null $revolving_fund
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Imprest newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Imprest newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Imprest query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Imprest whereAccrued($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Imprest whereAmount($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Imprest whereBalance($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Imprest whereCashierId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Imprest whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Imprest whereDeposit($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Imprest whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Imprest whereInterest($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Imprest whereInterestDate($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Imprest whereInterestRate($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Imprest whereMemberId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Imprest wherePaymentTypeId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Imprest whereReferenceNumber($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Imprest whereTransactionDate($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Imprest whereTransactionDatetime($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Imprest whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Imprest whereWithdrawal($value)
 * @mixin \Eloquent
 */
	#[\AllowDynamicProperties]
	class IdeHelperImprest {}
}

namespace App\Models{
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
 * @property-read \App\Models\AccountType $account_type
 * @property-read \Staudenmeir\LaravelAdjacencyList\Eloquent\Collection<int, \App\Models\ImprestAccount> $children
 * @property-read int|null $children_count
 * @property-read \App\Models\Member|null $member
 * @property-read \App\Models\ImprestAccount|null $parent
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Transaction> $transactions
 * @property-read int|null $transactions_count
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
	#[\AllowDynamicProperties]
	class IdeHelperImprestAccount {}
}

namespace App\Models{
/**
 * @property int $id
 * @property int|null $voucher_type_id
 * @property string $name
 * @property string|null $address
 * @property string $reference_number
 * @property string $voucher_number
 * @property string $description
 * @property \Carbon\CarbonImmutable $transaction_date
 * @property int $bookkeeper_id
 * @property int $is_legacy
 * @property \Carbon\CarbonImmutable|null $created_at
 * @property \Carbon\CarbonImmutable|null $updated_at
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\JournalEntryVoucherItem> $journal_entry_voucher_items
 * @property-read int|null $journal_entry_voucher_items_count
 * @property-read \App\Models\VoucherType|null $voucher_type
 * @method static \Illuminate\Database\Eloquent\Builder<static>|JournalEntryVoucher newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|JournalEntryVoucher newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|JournalEntryVoucher query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|JournalEntryVoucher whereAddress($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|JournalEntryVoucher whereBookkeeperId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|JournalEntryVoucher whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|JournalEntryVoucher whereDescription($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|JournalEntryVoucher whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|JournalEntryVoucher whereIsLegacy($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|JournalEntryVoucher whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|JournalEntryVoucher whereReferenceNumber($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|JournalEntryVoucher whereTransactionDate($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|JournalEntryVoucher whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|JournalEntryVoucher whereVoucherNumber($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|JournalEntryVoucher whereVoucherTypeId($value)
 * @mixin \Eloquent
 */
	#[\AllowDynamicProperties]
	class IdeHelperJournalEntryVoucher {}
}

namespace App\Models{
/**
 * @property int $id
 * @property int $journal_entry_voucher_id
 * @property int $account_id
 * @property numeric|null $credit
 * @property numeric|null $debit
 * @property array<array-key, mixed> $details
 * @property string $transaction_date
 * @property \Carbon\CarbonImmutable|null $created_at
 * @property \Carbon\CarbonImmutable|null $updated_at
 * @property-read \App\Models\Account $account
 * @property-read \App\Models\JournalEntryVoucher $journal_entry_voucher
 * @method static \Illuminate\Database\Eloquent\Builder<static>|JournalEntryVoucherItem newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|JournalEntryVoucherItem newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|JournalEntryVoucherItem query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|JournalEntryVoucherItem whereAccountId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|JournalEntryVoucherItem whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|JournalEntryVoucherItem whereCredit($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|JournalEntryVoucherItem whereDebit($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|JournalEntryVoucherItem whereDetails($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|JournalEntryVoucherItem whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|JournalEntryVoucherItem whereJournalEntryVoucherId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|JournalEntryVoucherItem whereTransactionDate($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|JournalEntryVoucherItem whereUpdatedAt($value)
 * @mixin \Eloquent
 */
	#[\AllowDynamicProperties]
	class IdeHelperJournalEntryVoucherItem {}
}

namespace App\Models{
/**
 * @property int $id
 * @property int $loan_account_id
 * @property int|null $loan_buyout_id
 * @property int $member_id
 * @property int $loan_application_id
 * @property int $loan_type_id
 * @property int|null $disbursement_voucher_id
 * @property string $reference_number
 * @property string|null $check_number
 * @property string $priority_number
 * @property numeric $gross_amount
 * @property numeric|null $net_amount
 * @property array<array-key, mixed> $disclosure_sheet_items
 * @property int $number_of_terms
 * @property numeric $interest_rate
 * @property numeric $interest
 * @property numeric $service_fee
 * @property numeric $cbu_amount
 * @property numeric $imprest_amount
 * @property numeric $insurance_amount
 * @property numeric $loan_buyout
 * @property numeric $deductions_amount
 * @property numeric $monthly_payment
 * @property numeric $outstanding_balance
 * @property \Carbon\CarbonImmutable $release_date
 * @property \Carbon\CarbonImmutable $transaction_date
 * @property bool $posted
 * @property \Carbon\CarbonImmutable|null $created_at
 * @property \Carbon\CarbonImmutable|null $updated_at
 * @property-read \App\Models\DisbursementVoucher|null $disbursement_voucher
 * @property-read mixed $deductions_list
 * @property-read mixed $maturity_date
 * @property-read mixed $last_payment_before_transaction_date
 * @property-read \App\Models\LoanPayment|null $last_payment
 * @property-read \App\Models\LoanAccount $loan_account
 * @property-read \App\Models\LoanApplication $loan_application
 * @property-read \App\Models\LoanType $loan_type
 * @property-read \App\Models\Member $member
 * @property-read mixed $net_amount_in_words
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\LoanPayment> $payments
 * @property-read int|null $payments_count
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Loan newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Loan newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Loan payable()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Loan pending()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Loan posted()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Loan query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Loan whereCbuAmount($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Loan whereCheckNumber($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Loan whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Loan whereDeductionsAmount($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Loan whereDisbursementVoucherId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Loan whereDisclosureSheetItems($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Loan whereGrossAmount($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Loan whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Loan whereImprestAmount($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Loan whereInsuranceAmount($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Loan whereInterest($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Loan whereInterestRate($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Loan whereLoanAccountId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Loan whereLoanApplicationId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Loan whereLoanBuyout($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Loan whereLoanBuyoutId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Loan whereLoanTypeId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Loan whereMemberId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Loan whereMonthlyPayment($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Loan whereNetAmount($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Loan whereNumberOfTerms($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Loan whereOutstandingBalance($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Loan wherePosted($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Loan wherePriorityNumber($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Loan whereReferenceNumber($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Loan whereReleaseDate($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Loan whereServiceFee($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Loan whereTransactionDate($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Loan whereUpdatedAt($value)
 * @mixin \Eloquent
 */
	#[\AllowDynamicProperties]
	class IdeHelperLoan {}
}

namespace App\Models{
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
 * @property-read \App\Models\AccountType $account_type
 * @property-read \Staudenmeir\LaravelAdjacencyList\Eloquent\Collection<int, \App\Models\LoanAccount> $children
 * @property-read int|null $children_count
 * @property-read \App\Models\Loan|null $loan
 * @property-read \App\Models\Member|null $member
 * @property-read \App\Models\LoanAccount|null $parent
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Transaction> $transactions
 * @property-read int|null $transactions_count
 * @property-read int $depth
 * @property-read string $path
 * @property-read string $fullname
 * @property-read \Staudenmeir\LaravelAdjacencyList\Eloquent\Collection<int, \App\Models\LoanAccount> $ancestors The model's recursive parents.
 * @property-read int|null $ancestors_count
 * @property-read \Staudenmeir\LaravelAdjacencyList\Eloquent\Collection<int, \App\Models\LoanAccount> $ancestorsAndSelf The model's recursive parents and itself.
 * @property-read int|null $ancestors_and_self_count
 * @property-read \Staudenmeir\LaravelAdjacencyList\Eloquent\Collection<int, \App\Models\LoanAccount> $bloodline The model's ancestors, descendants and itself.
 * @property-read int|null $bloodline_count
 * @property-read \Staudenmeir\LaravelAdjacencyList\Eloquent\Collection<int, \App\Models\LoanAccount> $childrenAndSelf The model's direct children and itself.
 * @property-read int|null $children_and_self_count
 * @property-read \Staudenmeir\LaravelAdjacencyList\Eloquent\Collection<int, \App\Models\LoanAccount> $descendants The model's recursive children.
 * @property-read int|null $descendants_count
 * @property-read \Staudenmeir\LaravelAdjacencyList\Eloquent\Collection<int, \App\Models\LoanAccount> $descendantsAndSelf The model's recursive children and itself.
 * @property-read int|null $descendants_and_self_count
 * @property-read \Staudenmeir\LaravelAdjacencyList\Eloquent\Collection<int, \App\Models\LoanAccount> $parentAndSelf The model's direct parent and itself.
 * @property-read int|null $parent_and_self_count
 * @property-read \App\Models\LoanAccount|null $rootAncestor The model's topmost parent.
 * @property-read \Staudenmeir\LaravelAdjacencyList\Eloquent\Collection<int, \App\Models\LoanAccount> $siblings The parent's other children.
 * @property-read int|null $siblings_count
 * @property-read \Staudenmeir\LaravelAdjacencyList\Eloquent\Collection<int, \App\Models\LoanAccount> $siblingsAndSelf All the parent's children.
 * @property-read int|null $siblings_and_self_count
 * @method static \Staudenmeir\LaravelAdjacencyList\Eloquent\Collection<int, static> all($columns = ['*'])
 * @method static \Staudenmeir\LaravelAdjacencyList\Eloquent\Builder<static>|LoanAccount breadthFirst()
 * @method static \Staudenmeir\LaravelAdjacencyList\Eloquent\Builder<static>|LoanAccount depthFirst()
 * @method static \Staudenmeir\LaravelAdjacencyList\Eloquent\Builder<static>|LoanAccount doesntHaveChildren()
 * @method static \Staudenmeir\LaravelAdjacencyList\Eloquent\Collection<int, static> get($columns = ['*'])
 * @method static \Staudenmeir\LaravelAdjacencyList\Eloquent\Builder<static>|LoanAccount getExpressionGrammar()
 * @method static \Staudenmeir\LaravelAdjacencyList\Eloquent\Builder<static>|LoanAccount hasChildren()
 * @method static \Staudenmeir\LaravelAdjacencyList\Eloquent\Builder<static>|LoanAccount hasParent()
 * @method static \Staudenmeir\LaravelAdjacencyList\Eloquent\Builder<static>|LoanAccount isLeaf()
 * @method static \Staudenmeir\LaravelAdjacencyList\Eloquent\Builder<static>|LoanAccount isRoot()
 * @method static \Staudenmeir\LaravelAdjacencyList\Eloquent\Builder<static>|LoanAccount newModelQuery()
 * @method static \Staudenmeir\LaravelAdjacencyList\Eloquent\Builder<static>|LoanAccount newQuery()
 * @method static \Staudenmeir\LaravelAdjacencyList\Eloquent\Builder<static>|LoanAccount query()
 * @method static \Staudenmeir\LaravelAdjacencyList\Eloquent\Builder<static>|LoanAccount tree($maxDepth = null)
 * @method static \Staudenmeir\LaravelAdjacencyList\Eloquent\Builder<static>|LoanAccount treeOf(\Illuminate\Database\Eloquent\Model|callable $constraint, $maxDepth = null)
 * @method static \Staudenmeir\LaravelAdjacencyList\Eloquent\Builder<static>|LoanAccount whereAccountTypeId($value)
 * @method static \Staudenmeir\LaravelAdjacencyList\Eloquent\Builder<static>|LoanAccount whereAccountableId($value)
 * @method static \Staudenmeir\LaravelAdjacencyList\Eloquent\Builder<static>|LoanAccount whereAccountableType($value)
 * @method static \Staudenmeir\LaravelAdjacencyList\Eloquent\Builder<static>|LoanAccount whereCreatedAt($value)
 * @method static \Staudenmeir\LaravelAdjacencyList\Eloquent\Builder<static>|LoanAccount whereDepth($operator, $value = null)
 * @method static \Staudenmeir\LaravelAdjacencyList\Eloquent\Builder<static>|LoanAccount whereId($value)
 * @method static \Staudenmeir\LaravelAdjacencyList\Eloquent\Builder<static>|LoanAccount whereMemberId($value)
 * @method static \Staudenmeir\LaravelAdjacencyList\Eloquent\Builder<static>|LoanAccount whereName($value)
 * @method static \Staudenmeir\LaravelAdjacencyList\Eloquent\Builder<static>|LoanAccount whereNumber($value)
 * @method static \Staudenmeir\LaravelAdjacencyList\Eloquent\Builder<static>|LoanAccount whereParentId($value)
 * @method static \Staudenmeir\LaravelAdjacencyList\Eloquent\Builder<static>|LoanAccount whereShowSum($value)
 * @method static \Staudenmeir\LaravelAdjacencyList\Eloquent\Builder<static>|LoanAccount whereSort($value)
 * @method static \Staudenmeir\LaravelAdjacencyList\Eloquent\Builder<static>|LoanAccount whereSumDescription($value)
 * @method static \Staudenmeir\LaravelAdjacencyList\Eloquent\Builder<static>|LoanAccount whereTag($value)
 * @method static \Staudenmeir\LaravelAdjacencyList\Eloquent\Builder<static>|LoanAccount whereUpdatedAt($value)
 * @method static \Staudenmeir\LaravelAdjacencyList\Eloquent\Builder<static>|LoanAccount withCode()
 * @method static \Staudenmeir\LaravelAdjacencyList\Eloquent\Builder<static>|LoanAccount withGlobalScopes(array $scopes)
 * @method static \Staudenmeir\LaravelAdjacencyList\Eloquent\Builder<static>|LoanAccount withRelationshipExpression($direction, callable $constraint, $initialDepth, $from = null, $maxDepth = null)
 * @mixin \Eloquent
 */
	#[\AllowDynamicProperties]
	class IdeHelperLoanAccount {}
}

namespace App\Models{
/**
 * @property int $id
 * @property int $member_id
 * @property int|null $processor_id
 * @property int $loan_type_id
 * @property numeric $desired_amount
 * @property numeric $cbu_amount
 * @property int $number_of_terms
 * @property string|null $reference_number
 * @property string|null $priority_number
 * @property string|null $purpose
 * @property numeric $monthly_payment
 * @property int $status
 * @property \Carbon\CarbonImmutable $transaction_date
 * @property int|null $disapproval_reason_id
 * @property \Carbon\CarbonImmutable|null $disapproval_date
 * @property \Carbon\CarbonImmutable|null $approval_date
 * @property \Carbon\CarbonImmutable|null $payment_start_date
 * @property \Carbon\CarbonImmutable|null $surcharge_start_date
 * @property \Spatie\LaravelData\DataCollection $approvals
 * @property string|null $remarks
 * @property \Carbon\CarbonImmutable|null $created_at
 * @property \Carbon\CarbonImmutable|null $updated_at
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\LoanApplicationComaker> $comakers
 * @property-read int|null $comakers_count
 * @property-read mixed $desired_amount_in_words
 * @property-read \App\Models\DisapprovalReason|null $disapproval_reason
 * @property-read mixed $status_name
 * @property-read \App\Models\Loan|null $loan
 * @property-read \App\Models\LoanType $loan_type
 * @property-read \App\Models\Member $member
 * @property-read \App\Models\User|null $processor
 * @method static \Illuminate\Database\Eloquent\Builder<static>|LoanApplication newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|LoanApplication newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|LoanApplication query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|LoanApplication whereApprovalDate($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|LoanApplication whereApprovals($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|LoanApplication whereCbuAmount($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|LoanApplication whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|LoanApplication whereDesiredAmount($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|LoanApplication whereDisapprovalDate($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|LoanApplication whereDisapprovalReasonId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|LoanApplication whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|LoanApplication whereLoanTypeId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|LoanApplication whereMemberId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|LoanApplication whereMonthlyPayment($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|LoanApplication whereNumberOfTerms($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|LoanApplication wherePaymentStartDate($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|LoanApplication wherePriorityNumber($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|LoanApplication whereProcessorId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|LoanApplication wherePurpose($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|LoanApplication whereReferenceNumber($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|LoanApplication whereRemarks($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|LoanApplication whereStatus($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|LoanApplication whereSurchargeStartDate($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|LoanApplication whereTransactionDate($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|LoanApplication whereUpdatedAt($value)
 * @mixin \Eloquent
 */
	#[\AllowDynamicProperties]
	class IdeHelperLoanApplication {}
}

namespace App\Models{
/**
 * @property int $id
 * @property int $loan_application_id
 * @property int $member_id
 * @property \Carbon\CarbonImmutable|null $created_at
 * @property \Carbon\CarbonImmutable|null $updated_at
 * @property-read \App\Models\LoanApplication $loan_application
 * @property-read \App\Models\Member $member
 * @method static \Illuminate\Database\Eloquent\Builder<static>|LoanApplicationComaker newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|LoanApplicationComaker newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|LoanApplicationComaker query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|LoanApplicationComaker whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|LoanApplicationComaker whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|LoanApplicationComaker whereLoanApplicationId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|LoanApplicationComaker whereMemberId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|LoanApplicationComaker whereUpdatedAt($value)
 * @mixin \Eloquent
 */
	#[\AllowDynamicProperties]
	class IdeHelperLoanApplicationComaker {}
}

namespace App\Models{
/**
 * @property int $id
 * @property \Carbon\CarbonImmutable $date
 * @property string|null $billable_date
 * @property int|null $payment_type_id
 * @property int|null $member_type_id
 * @property int|null $member_subtype_id
 * @property string|null $reference_number
 * @property string|null $name
 * @property string|null $or_number
 * @property \Carbon\CarbonImmutable|null $or_date
 * @property int $loan_type_id
 * @property int|null $cashier_id
 * @property bool $posted
 * @property bool $for_or
 * @property \Carbon\CarbonImmutable|null $created_at
 * @property \Carbon\CarbonImmutable|null $updated_at
 * @property-read mixed $can_for_or
 * @property-read mixed $can_post_payments
 * @property-read mixed $or_approved
 * @property-read \App\Models\User|null $cashier
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\LoanBillingPayment> $loan_billing_payments
 * @property-read int|null $loan_billing_payments_count
 * @property-read \App\Models\LoanType $loan_type
 * @property-read \App\Models\PaymentType|null $payment_type
 * @method static \Illuminate\Database\Eloquent\Builder<static>|LoanBilling newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|LoanBilling newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|LoanBilling query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|LoanBilling whereBillableDate($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|LoanBilling whereCashierId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|LoanBilling whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|LoanBilling whereDate($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|LoanBilling whereForOr($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|LoanBilling whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|LoanBilling whereLoanTypeId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|LoanBilling whereMemberSubtypeId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|LoanBilling whereMemberTypeId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|LoanBilling whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|LoanBilling whereOrDate($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|LoanBilling whereOrNumber($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|LoanBilling wherePaymentTypeId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|LoanBilling wherePosted($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|LoanBilling whereReferenceNumber($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|LoanBilling whereUpdatedAt($value)
 * @mixin \Eloquent
 */
	#[\AllowDynamicProperties]
	class IdeHelperLoanBilling {}
}

namespace App\Models{
/**
 * @property int $id
 * @property int $loan_billing_id
 * @property int $member_id
 * @property int $loan_id
 * @property numeric $amount_due
 * @property numeric $amount_paid
 * @property bool $posted
 * @property \Carbon\CarbonImmutable|null $created_at
 * @property \Carbon\CarbonImmutable|null $updated_at
 * @property-read \App\Models\Loan $loan
 * @property-read \App\Models\LoanBilling $loan_billing
 * @property-read \App\Models\Member $member
 * @method static \Illuminate\Database\Eloquent\Builder<static>|LoanBillingPayment newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|LoanBillingPayment newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|LoanBillingPayment query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|LoanBillingPayment whereAmountDue($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|LoanBillingPayment whereAmountPaid($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|LoanBillingPayment whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|LoanBillingPayment whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|LoanBillingPayment whereLoanBillingId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|LoanBillingPayment whereLoanId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|LoanBillingPayment whereMemberId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|LoanBillingPayment wherePosted($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|LoanBillingPayment whereUpdatedAt($value)
 * @mixin \Eloquent
 */
	#[\AllowDynamicProperties]
	class IdeHelperLoanBillingPayment {}
}

namespace App\Models{
/**
 * @property int $id
 * @property bool $buy_out
 * @property int $loan_id
 * @property int $member_id
 * @property numeric $amount
 * @property numeric $interest_payment
 * @property numeric $principal_payment
 * @property numeric $unpaid_interest
 * @property numeric $surcharge_payment
 * @property int $payment_type_id
 * @property string $reference_number
 * @property string|null $remarks
 * @property \Carbon\CarbonImmutable $transaction_date
 * @property int|null $cashier_id
 * @property \Carbon\CarbonImmutable|null $created_at
 * @property \Carbon\CarbonImmutable|null $updated_at
 * @property-read \App\Models\User|null $cashier
 * @property-read \App\Models\Loan $loan
 * @property-read \App\Models\Member $member
 * @method static \Illuminate\Database\Eloquent\Builder<static>|LoanPayment newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|LoanPayment newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|LoanPayment query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|LoanPayment whereAmount($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|LoanPayment whereBuyOut($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|LoanPayment whereCashierId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|LoanPayment whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|LoanPayment whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|LoanPayment whereInterestPayment($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|LoanPayment whereLoanId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|LoanPayment whereMemberId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|LoanPayment wherePaymentTypeId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|LoanPayment wherePrincipalPayment($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|LoanPayment whereReferenceNumber($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|LoanPayment whereRemarks($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|LoanPayment whereSurchargePayment($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|LoanPayment whereTransactionDate($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|LoanPayment whereUnpaidInterest($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|LoanPayment whereUpdatedAt($value)
 * @mixin \Eloquent
 */
	#[\AllowDynamicProperties]
	class IdeHelperLoanPayment {}
}

namespace App\Models{
/**
 * @property int $id
 * @property string $code
 * @property string $name
 * @property numeric $minimum_cbu
 * @property numeric $max_amount
 * @property numeric $interest_rate
 * @property numeric $surcharge_rate
 * @property numeric $service_fee
 * @property numeric $cbu_common
 * @property numeric $imprest
 * @property numeric $insurance
 * @property int $has_monthly_amortization
 * @property \Carbon\CarbonImmutable|null $created_at
 * @property \Carbon\CarbonImmutable|null $updated_at
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Loan> $loans
 * @property-read int|null $loans_count
 * @method static \Illuminate\Database\Eloquent\Builder<static>|LoanType newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|LoanType newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|LoanType query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|LoanType whereCbuCommon($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|LoanType whereCode($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|LoanType whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|LoanType whereHasMonthlyAmortization($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|LoanType whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|LoanType whereImprest($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|LoanType whereInsurance($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|LoanType whereInterestRate($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|LoanType whereMaxAmount($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|LoanType whereMinimumCbu($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|LoanType whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|LoanType whereServiceFee($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|LoanType whereSurchargeRate($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|LoanType whereUpdatedAt($value)
 * @mixin \Eloquent
 */
	#[\AllowDynamicProperties]
	class IdeHelperLoanType {}
}

namespace App\Models{
/**
 * @property int $id
 * @property int $member_id
 * @property int $payment_type_id
 * @property string $reference_number
 * @property numeric $amount
 * @property numeric|null $deposit
 * @property numeric|null $withdrawal
 * @property numeric $interest_rate
 * @property numeric $interest
 * @property \Carbon\CarbonImmutable $transaction_date
 * @property \Carbon\CarbonImmutable|null $interest_date
 * @property numeric $balance
 * @property bool $accrued
 * @property int|null $cashier_id
 * @property \Carbon\CarbonImmutable|null $created_at
 * @property \Carbon\CarbonImmutable|null $updated_at
 * @property-read \App\Models\User|null $cashier
 * @property-read \App\Models\Member $member
 * @property-read \App\Models\RevolvingFund|null $revolving_fund
 * @method static \Illuminate\Database\Eloquent\Builder<static>|LoveGift newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|LoveGift newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|LoveGift query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|LoveGift whereAccrued($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|LoveGift whereAmount($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|LoveGift whereBalance($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|LoveGift whereCashierId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|LoveGift whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|LoveGift whereDeposit($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|LoveGift whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|LoveGift whereInterest($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|LoveGift whereInterestDate($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|LoveGift whereInterestRate($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|LoveGift whereMemberId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|LoveGift wherePaymentTypeId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|LoveGift whereReferenceNumber($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|LoveGift whereTransactionDate($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|LoveGift whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|LoveGift whereWithdrawal($value)
 * @mixin \Eloquent
 */
	#[\AllowDynamicProperties]
	class IdeHelperLoveGift {}
}

namespace App\Models{
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
 * @property-read \App\Models\AccountType $account_type
 * @property-read \Staudenmeir\LaravelAdjacencyList\Eloquent\Collection<int, \App\Models\LoveGiftAccount> $children
 * @property-read int|null $children_count
 * @property-read \App\Models\Member|null $member
 * @property-read \App\Models\LoveGiftAccount|null $parent
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Transaction> $transactions
 * @property-read int|null $transactions_count
 * @property-read int $depth
 * @property-read string $path
 * @property-read string $fullname
 * @property-read \Staudenmeir\LaravelAdjacencyList\Eloquent\Collection<int, \App\Models\LoveGiftAccount> $ancestors The model's recursive parents.
 * @property-read int|null $ancestors_count
 * @property-read \Staudenmeir\LaravelAdjacencyList\Eloquent\Collection<int, \App\Models\LoveGiftAccount> $ancestorsAndSelf The model's recursive parents and itself.
 * @property-read int|null $ancestors_and_self_count
 * @property-read \Staudenmeir\LaravelAdjacencyList\Eloquent\Collection<int, \App\Models\LoveGiftAccount> $bloodline The model's ancestors, descendants and itself.
 * @property-read int|null $bloodline_count
 * @property-read \Staudenmeir\LaravelAdjacencyList\Eloquent\Collection<int, \App\Models\LoveGiftAccount> $childrenAndSelf The model's direct children and itself.
 * @property-read int|null $children_and_self_count
 * @property-read \Staudenmeir\LaravelAdjacencyList\Eloquent\Collection<int, \App\Models\LoveGiftAccount> $descendants The model's recursive children.
 * @property-read int|null $descendants_count
 * @property-read \Staudenmeir\LaravelAdjacencyList\Eloquent\Collection<int, \App\Models\LoveGiftAccount> $descendantsAndSelf The model's recursive children and itself.
 * @property-read int|null $descendants_and_self_count
 * @property-read \Staudenmeir\LaravelAdjacencyList\Eloquent\Collection<int, \App\Models\LoveGiftAccount> $parentAndSelf The model's direct parent and itself.
 * @property-read int|null $parent_and_self_count
 * @property-read \App\Models\LoveGiftAccount|null $rootAncestor The model's topmost parent.
 * @property-read \Staudenmeir\LaravelAdjacencyList\Eloquent\Collection<int, \App\Models\LoveGiftAccount> $siblings The parent's other children.
 * @property-read int|null $siblings_count
 * @property-read \Staudenmeir\LaravelAdjacencyList\Eloquent\Collection<int, \App\Models\LoveGiftAccount> $siblingsAndSelf All the parent's children.
 * @property-read int|null $siblings_and_self_count
 * @method static \Staudenmeir\LaravelAdjacencyList\Eloquent\Collection<int, static> all($columns = ['*'])
 * @method static \Staudenmeir\LaravelAdjacencyList\Eloquent\Builder<static>|LoveGiftAccount breadthFirst()
 * @method static \Staudenmeir\LaravelAdjacencyList\Eloquent\Builder<static>|LoveGiftAccount depthFirst()
 * @method static \Staudenmeir\LaravelAdjacencyList\Eloquent\Builder<static>|LoveGiftAccount doesntHaveChildren()
 * @method static \Staudenmeir\LaravelAdjacencyList\Eloquent\Collection<int, static> get($columns = ['*'])
 * @method static \Staudenmeir\LaravelAdjacencyList\Eloquent\Builder<static>|LoveGiftAccount getExpressionGrammar()
 * @method static \Staudenmeir\LaravelAdjacencyList\Eloquent\Builder<static>|LoveGiftAccount hasChildren()
 * @method static \Staudenmeir\LaravelAdjacencyList\Eloquent\Builder<static>|LoveGiftAccount hasParent()
 * @method static \Staudenmeir\LaravelAdjacencyList\Eloquent\Builder<static>|LoveGiftAccount isLeaf()
 * @method static \Staudenmeir\LaravelAdjacencyList\Eloquent\Builder<static>|LoveGiftAccount isRoot()
 * @method static \Staudenmeir\LaravelAdjacencyList\Eloquent\Builder<static>|LoveGiftAccount newModelQuery()
 * @method static \Staudenmeir\LaravelAdjacencyList\Eloquent\Builder<static>|LoveGiftAccount newQuery()
 * @method static \Staudenmeir\LaravelAdjacencyList\Eloquent\Builder<static>|LoveGiftAccount query()
 * @method static \Staudenmeir\LaravelAdjacencyList\Eloquent\Builder<static>|LoveGiftAccount tree($maxDepth = null)
 * @method static \Staudenmeir\LaravelAdjacencyList\Eloquent\Builder<static>|LoveGiftAccount treeOf(\Illuminate\Database\Eloquent\Model|callable $constraint, $maxDepth = null)
 * @method static \Staudenmeir\LaravelAdjacencyList\Eloquent\Builder<static>|LoveGiftAccount whereAccountTypeId($value)
 * @method static \Staudenmeir\LaravelAdjacencyList\Eloquent\Builder<static>|LoveGiftAccount whereAccountableId($value)
 * @method static \Staudenmeir\LaravelAdjacencyList\Eloquent\Builder<static>|LoveGiftAccount whereAccountableType($value)
 * @method static \Staudenmeir\LaravelAdjacencyList\Eloquent\Builder<static>|LoveGiftAccount whereCreatedAt($value)
 * @method static \Staudenmeir\LaravelAdjacencyList\Eloquent\Builder<static>|LoveGiftAccount whereDepth($operator, $value = null)
 * @method static \Staudenmeir\LaravelAdjacencyList\Eloquent\Builder<static>|LoveGiftAccount whereId($value)
 * @method static \Staudenmeir\LaravelAdjacencyList\Eloquent\Builder<static>|LoveGiftAccount whereMemberId($value)
 * @method static \Staudenmeir\LaravelAdjacencyList\Eloquent\Builder<static>|LoveGiftAccount whereName($value)
 * @method static \Staudenmeir\LaravelAdjacencyList\Eloquent\Builder<static>|LoveGiftAccount whereNumber($value)
 * @method static \Staudenmeir\LaravelAdjacencyList\Eloquent\Builder<static>|LoveGiftAccount whereParentId($value)
 * @method static \Staudenmeir\LaravelAdjacencyList\Eloquent\Builder<static>|LoveGiftAccount whereShowSum($value)
 * @method static \Staudenmeir\LaravelAdjacencyList\Eloquent\Builder<static>|LoveGiftAccount whereSort($value)
 * @method static \Staudenmeir\LaravelAdjacencyList\Eloquent\Builder<static>|LoveGiftAccount whereSumDescription($value)
 * @method static \Staudenmeir\LaravelAdjacencyList\Eloquent\Builder<static>|LoveGiftAccount whereTag($value)
 * @method static \Staudenmeir\LaravelAdjacencyList\Eloquent\Builder<static>|LoveGiftAccount whereUpdatedAt($value)
 * @method static \Staudenmeir\LaravelAdjacencyList\Eloquent\Builder<static>|LoveGiftAccount withCode()
 * @method static \Staudenmeir\LaravelAdjacencyList\Eloquent\Builder<static>|LoveGiftAccount withGlobalScopes(array $scopes)
 * @method static \Staudenmeir\LaravelAdjacencyList\Eloquent\Builder<static>|LoveGiftAccount withRelationshipExpression($direction, callable $constraint, $initialDepth, $from = null, $maxDepth = null)
 * @mixin \Eloquent
 */
	#[\AllowDynamicProperties]
	class IdeHelperLoveGiftAccount {}
}

namespace App\Models{
/**
 * @property int $id
 * @property string|null $mpc_code
 * @property array<array-key, mixed> $member_ids
 * @property int $is_organization
 * @property int|null $member_type_id
 * @property int|null $member_subtype_id
 * @property int|null $division_id
 * @property string|null $section
 * @property string|null $grade
 * @property int|null $user_id
 * @property string $first_name
 * @property string|null $last_name
 * @property string|null $middle_name
 * @property string|null $middle_initial
 * @property string|null $alt_full_name
 * @property string|null $full_name
 * @property string|null $tin
 * @property int|null $gender_id
 * @property int|null $civil_status_id
 * @property int $patronage_status_id
 * @property \Carbon\CarbonImmutable $membership_date
 * @property string|null $contact
 * @property \Carbon\CarbonImmutable|null $dob
 * @property string|null $place_of_birth
 * @property string|null $address
 * @property int|null $occupation_id
 * @property string|null $occupation_description
 * @property string|null $present_employer
 * @property string|null $highest_educational_attainment
 * @property \Spatie\LaravelData\DataCollection $dependents
 * @property int|null $dependents_count
 * @property int|null $religion_id
 * @property numeric|null $annual_income
 * @property array<array-key, mixed>|null $other_income_sources
 * @property int|null $region_id
 * @property int|null $province_id
 * @property int|null $municipality_id
 * @property int|null $barangay_id
 * @property string|null $terminated_at
 * @property \Carbon\CarbonImmutable|null $created_at
 * @property \Carbon\CarbonImmutable|null $updated_at
 * @property-read \Staudenmeir\LaravelAdjacencyList\Eloquent\Collection<int, \App\Models\Account> $accounts
 * @property-read int|null $accounts_count
 * @property-read \App\Models\CapitalSubscription|null $active_capital_subscription
 * @property-read \App\Models\Barangay|null $barangay
 * @property-read \App\Models\Account|null $capital_subscription_account
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\CapitalSubscriptionPayment> $capital_subscription_payments
 * @property-read int|null $capital_subscription_payments_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\CapitalSubscription> $capital_subscriptions
 * @property-read int|null $capital_subscriptions_count
 * @property-read \App\Models\CivilStatus|null $civil_status
 * @property-read \App\Models\Division|null $division
 * @property-read \App\Models\Account|null $existing_capital_subscription_account
 * @property-read \App\Models\Gender|null $gender
 * @property-read mixed $age
 * @property-read mixed $members
 * @property-read \App\Models\ImprestAccount|null $imprest_account
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Imprest> $imprests
 * @property-read int|null $imprests_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Imprest> $imprests_no_interest
 * @property-read int|null $imprests_no_interest_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Imprest> $imprests_unaccrued
 * @property-read int|null $imprests_unaccrued_count
 * @property-read \App\Models\CapitalSubscription|null $initial_capital_subscription
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\LoanApplication> $loan_applications
 * @property-read int|null $loan_applications_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Loan> $loans
 * @property-read int|null $loans_count
 * @property-read \App\Models\LoveGiftAccount|null $love_gift_account
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\LoveGift> $love_gifts
 * @property-read int|null $love_gifts_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\LoveGift> $love_gifts_no_interest
 * @property-read int|null $love_gifts_no_interest_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\LoveGift> $love_gifts_unaccrued
 * @property-read int|null $love_gifts_unaccrued_count
 * @property-read \Spatie\MediaLibrary\MediaCollections\Models\Collections\MediaCollection<int, \Spatie\MediaLibrary\MediaCollections\Models\Media> $media
 * @property-read int|null $media_count
 * @property-read \App\Models\MemberSubtype|null $member_subtype
 * @property-read \App\Models\MemberType|null $member_type
 * @property-read \App\Models\MembershipStatus|null $membership_acceptance
 * @property-read \App\Models\MembershipStatus|null $membership_termination
 * @property-read \App\Models\Municipality|null $municipality
 * @property-read \App\Models\Occupation|null $occupation
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\OfficersList> $officers_list
 * @property-read int|null $officers_list_count
 * @property-read \App\Models\PatronageStatus $patronage_status
 * @property-read \App\Models\Province|null $province
 * @property-read \App\Models\Region|null $region
 * @property-read \App\Models\Religion|null $religion
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Saving> $savings
 * @property-read int|null $savings_count
 * @property-read \Staudenmeir\LaravelAdjacencyList\Eloquent\Collection<int, \App\Models\SavingsAccount> $savings_accounts
 * @property-read int|null $savings_accounts_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Saving> $savings_no_interest
 * @property-read int|null $savings_no_interest_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Saving> $savings_unaccrued
 * @property-read int|null $savings_unaccrued_count
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Member newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Member newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Member query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Member whereAddress($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Member whereAltFullName($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Member whereAnnualIncome($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Member whereBarangayId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Member whereCivilStatusId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Member whereContact($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Member whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Member whereDependents($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Member whereDependentsCount($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Member whereDivisionId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Member whereDob($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Member whereFirstName($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Member whereFullName($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Member whereGenderId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Member whereGrade($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Member whereHighestEducationalAttainment($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Member whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Member whereIsOrganization($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Member whereLastName($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Member whereMemberIds($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Member whereMemberSubtypeId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Member whereMemberTypeId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Member whereMembershipDate($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Member whereMiddleInitial($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Member whereMiddleName($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Member whereMpcCode($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Member whereMunicipalityId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Member whereOccupationDescription($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Member whereOccupationId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Member whereOtherIncomeSources($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Member wherePatronageStatusId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Member wherePlaceOfBirth($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Member wherePresentEmployer($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Member whereProvinceId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Member whereRegionId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Member whereReligionId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Member whereSection($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Member whereTerminatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Member whereTin($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Member whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Member whereUserId($value)
 * @mixin \Eloquent
 */
	#[\AllowDynamicProperties]
	class IdeHelperMember {}
}

namespace App\Models{
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
 * @property-read \App\Models\AccountType $account_type
 * @property-read \Staudenmeir\LaravelAdjacencyList\Eloquent\Collection<int, \App\Models\MemberAccount> $children
 * @property-read int|null $children_count
 * @property-read \App\Models\Member|null $member
 * @property-read \App\Models\MemberAccount|null $parent
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Transaction> $transactions
 * @property-read int|null $transactions_count
 * @property-read int $depth
 * @property-read string $path
 * @property-read string $fullname
 * @property-read \Staudenmeir\LaravelAdjacencyList\Eloquent\Collection<int, \App\Models\MemberAccount> $ancestors The model's recursive parents.
 * @property-read int|null $ancestors_count
 * @property-read \Staudenmeir\LaravelAdjacencyList\Eloquent\Collection<int, \App\Models\MemberAccount> $ancestorsAndSelf The model's recursive parents and itself.
 * @property-read int|null $ancestors_and_self_count
 * @property-read \Staudenmeir\LaravelAdjacencyList\Eloquent\Collection<int, \App\Models\MemberAccount> $bloodline The model's ancestors, descendants and itself.
 * @property-read int|null $bloodline_count
 * @property-read \Staudenmeir\LaravelAdjacencyList\Eloquent\Collection<int, \App\Models\MemberAccount> $childrenAndSelf The model's direct children and itself.
 * @property-read int|null $children_and_self_count
 * @property-read \Staudenmeir\LaravelAdjacencyList\Eloquent\Collection<int, \App\Models\MemberAccount> $descendants The model's recursive children.
 * @property-read int|null $descendants_count
 * @property-read \Staudenmeir\LaravelAdjacencyList\Eloquent\Collection<int, \App\Models\MemberAccount> $descendantsAndSelf The model's recursive children and itself.
 * @property-read int|null $descendants_and_self_count
 * @property-read \Staudenmeir\LaravelAdjacencyList\Eloquent\Collection<int, \App\Models\MemberAccount> $parentAndSelf The model's direct parent and itself.
 * @property-read int|null $parent_and_self_count
 * @property-read \App\Models\MemberAccount|null $rootAncestor The model's topmost parent.
 * @property-read \Staudenmeir\LaravelAdjacencyList\Eloquent\Collection<int, \App\Models\MemberAccount> $siblings The parent's other children.
 * @property-read int|null $siblings_count
 * @property-read \Staudenmeir\LaravelAdjacencyList\Eloquent\Collection<int, \App\Models\MemberAccount> $siblingsAndSelf All the parent's children.
 * @property-read int|null $siblings_and_self_count
 * @method static \Staudenmeir\LaravelAdjacencyList\Eloquent\Collection<int, static> all($columns = ['*'])
 * @method static \Staudenmeir\LaravelAdjacencyList\Eloquent\Builder<static>|MemberAccount breadthFirst()
 * @method static \Staudenmeir\LaravelAdjacencyList\Eloquent\Builder<static>|MemberAccount depthFirst()
 * @method static \Staudenmeir\LaravelAdjacencyList\Eloquent\Builder<static>|MemberAccount doesntHaveChildren()
 * @method static \Staudenmeir\LaravelAdjacencyList\Eloquent\Collection<int, static> get($columns = ['*'])
 * @method static \Staudenmeir\LaravelAdjacencyList\Eloquent\Builder<static>|MemberAccount getExpressionGrammar()
 * @method static \Staudenmeir\LaravelAdjacencyList\Eloquent\Builder<static>|MemberAccount hasChildren()
 * @method static \Staudenmeir\LaravelAdjacencyList\Eloquent\Builder<static>|MemberAccount hasParent()
 * @method static \Staudenmeir\LaravelAdjacencyList\Eloquent\Builder<static>|MemberAccount isLeaf()
 * @method static \Staudenmeir\LaravelAdjacencyList\Eloquent\Builder<static>|MemberAccount isRoot()
 * @method static \Staudenmeir\LaravelAdjacencyList\Eloquent\Builder<static>|MemberAccount newModelQuery()
 * @method static \Staudenmeir\LaravelAdjacencyList\Eloquent\Builder<static>|MemberAccount newQuery()
 * @method static \Staudenmeir\LaravelAdjacencyList\Eloquent\Builder<static>|MemberAccount query()
 * @method static \Staudenmeir\LaravelAdjacencyList\Eloquent\Builder<static>|MemberAccount tree($maxDepth = null)
 * @method static \Staudenmeir\LaravelAdjacencyList\Eloquent\Builder<static>|MemberAccount treeOf(\Illuminate\Database\Eloquent\Model|callable $constraint, $maxDepth = null)
 * @method static \Staudenmeir\LaravelAdjacencyList\Eloquent\Builder<static>|MemberAccount whereAccountTypeId($value)
 * @method static \Staudenmeir\LaravelAdjacencyList\Eloquent\Builder<static>|MemberAccount whereAccountableId($value)
 * @method static \Staudenmeir\LaravelAdjacencyList\Eloquent\Builder<static>|MemberAccount whereAccountableType($value)
 * @method static \Staudenmeir\LaravelAdjacencyList\Eloquent\Builder<static>|MemberAccount whereCreatedAt($value)
 * @method static \Staudenmeir\LaravelAdjacencyList\Eloquent\Builder<static>|MemberAccount whereDepth($operator, $value = null)
 * @method static \Staudenmeir\LaravelAdjacencyList\Eloquent\Builder<static>|MemberAccount whereId($value)
 * @method static \Staudenmeir\LaravelAdjacencyList\Eloquent\Builder<static>|MemberAccount whereMemberId($value)
 * @method static \Staudenmeir\LaravelAdjacencyList\Eloquent\Builder<static>|MemberAccount whereName($value)
 * @method static \Staudenmeir\LaravelAdjacencyList\Eloquent\Builder<static>|MemberAccount whereNumber($value)
 * @method static \Staudenmeir\LaravelAdjacencyList\Eloquent\Builder<static>|MemberAccount whereParentId($value)
 * @method static \Staudenmeir\LaravelAdjacencyList\Eloquent\Builder<static>|MemberAccount whereShowSum($value)
 * @method static \Staudenmeir\LaravelAdjacencyList\Eloquent\Builder<static>|MemberAccount whereSort($value)
 * @method static \Staudenmeir\LaravelAdjacencyList\Eloquent\Builder<static>|MemberAccount whereSumDescription($value)
 * @method static \Staudenmeir\LaravelAdjacencyList\Eloquent\Builder<static>|MemberAccount whereTag($value)
 * @method static \Staudenmeir\LaravelAdjacencyList\Eloquent\Builder<static>|MemberAccount whereUpdatedAt($value)
 * @method static \Staudenmeir\LaravelAdjacencyList\Eloquent\Builder<static>|MemberAccount withCode()
 * @method static \Staudenmeir\LaravelAdjacencyList\Eloquent\Builder<static>|MemberAccount withGlobalScopes(array $scopes)
 * @method static \Staudenmeir\LaravelAdjacencyList\Eloquent\Builder<static>|MemberAccount withRelationshipExpression($direction, callable $constraint, $initialDepth, $from = null, $maxDepth = null)
 * @mixin \Eloquent
 */
	#[\AllowDynamicProperties]
	class IdeHelperMemberAccount {}
}

namespace App\Models{
/**
 * @property int $id
 * @property string $name
 * @property int $member_type_id
 * @property \Carbon\CarbonImmutable|null $created_at
 * @property \Carbon\CarbonImmutable|null $updated_at
 * @method static \Illuminate\Database\Eloquent\Builder<static>|MemberSubtype newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|MemberSubtype newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|MemberSubtype query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|MemberSubtype whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|MemberSubtype whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|MemberSubtype whereMemberTypeId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|MemberSubtype whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|MemberSubtype whereUpdatedAt($value)
 * @mixin \Eloquent
 */
	#[\AllowDynamicProperties]
	class IdeHelperMemberSubtype {}
}

namespace App\Models{
/**
 * @property int $id
 * @property string $name
 * @property numeric $surcharge_rate
 * @property numeric $par_value
 * @property numeric $default_number_of_shares
 * @property numeric $default_amount_subscribed
 * @property numeric $minimum_initial_payment
 * @property int $initial_number_of_terms
 * @property int $additional_number_of_terms
 * @property \Carbon\CarbonImmutable|null $created_at
 * @property \Carbon\CarbonImmutable|null $updated_at
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Member> $members
 * @property-read int|null $members_count
 * @method static \Illuminate\Database\Eloquent\Builder<static>|MemberType newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|MemberType newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|MemberType query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|MemberType whereAdditionalNumberOfTerms($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|MemberType whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|MemberType whereDefaultAmountSubscribed($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|MemberType whereDefaultNumberOfShares($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|MemberType whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|MemberType whereInitialNumberOfTerms($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|MemberType whereMinimumInitialPayment($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|MemberType whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|MemberType whereParValue($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|MemberType whereSurchargeRate($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|MemberType whereUpdatedAt($value)
 * @mixin \Eloquent
 */
	#[\AllowDynamicProperties]
	class IdeHelperMemberType {}
}

namespace App\Models{
/**
 * @property int $id
 * @property int $member_id
 * @property int $type
 * @property string|null $bod_resolution
 * @property \Carbon\CarbonImmutable $effectivity_date
 * @property \Carbon\CarbonImmutable|null $created_at
 * @property \Carbon\CarbonImmutable|null $updated_at
 * @property-read \App\Models\Member $member
 * @method static \Illuminate\Database\Eloquent\Builder<static>|MembershipStatus newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|MembershipStatus newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|MembershipStatus query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|MembershipStatus whereBodResolution($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|MembershipStatus whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|MembershipStatus whereEffectivityDate($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|MembershipStatus whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|MembershipStatus whereMemberId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|MembershipStatus whereType($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|MembershipStatus whereUpdatedAt($value)
 * @mixin \Eloquent
 */
	#[\AllowDynamicProperties]
	class IdeHelperMembershipStatus {}
}

namespace App\Models{
/**
 * @property int $id
 * @property \App\Enums\MsoBillingType $type
 * @property \Carbon\CarbonImmutable $date
 * @property string|null $billable_date
 * @property int|null $payment_type_id
 * @property string|null $reference_number
 * @property string|null $name
 * @property string|null $or_number
 * @property \Carbon\CarbonImmutable|null $or_date
 * @property int|null $cashier_id
 * @property bool $posted
 * @property bool $for_or
 * @property \Carbon\CarbonImmutable|null $created_at
 * @property \Carbon\CarbonImmutable|null $updated_at
 * @property-read mixed $or_approved
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\MsoBillingPayment> $payments
 * @property-read int|null $payments_count
 * @method static \Illuminate\Database\Eloquent\Builder<static>|MsoBilling newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|MsoBilling newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|MsoBilling query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|MsoBilling whereBillableDate($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|MsoBilling whereCashierId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|MsoBilling whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|MsoBilling whereDate($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|MsoBilling whereForOr($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|MsoBilling whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|MsoBilling whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|MsoBilling whereOrDate($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|MsoBilling whereOrNumber($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|MsoBilling wherePaymentTypeId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|MsoBilling wherePosted($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|MsoBilling whereReferenceNumber($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|MsoBilling whereType($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|MsoBilling whereUpdatedAt($value)
 * @mixin \Eloquent
 */
	#[\AllowDynamicProperties]
	class IdeHelperMsoBilling {}
}

namespace App\Models{
/**
 * @property int $id
 * @property int $mso_billing_id
 * @property int $account_id
 * @property int|null $member_id
 * @property string $payee
 * @property numeric $amount_due
 * @property numeric $amount_paid
 * @property int $posted
 * @property \Carbon\CarbonImmutable|null $created_at
 * @property \Carbon\CarbonImmutable|null $updated_at
 * @property-read \App\Models\Account $account
 * @property-read \App\Models\Member|null $member
 * @property-read \App\Models\MsoBilling $mso_billing
 * @method static \Illuminate\Database\Eloquent\Builder<static>|MsoBillingPayment newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|MsoBillingPayment newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|MsoBillingPayment query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|MsoBillingPayment whereAccountId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|MsoBillingPayment whereAmountDue($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|MsoBillingPayment whereAmountPaid($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|MsoBillingPayment whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|MsoBillingPayment whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|MsoBillingPayment whereMemberId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|MsoBillingPayment whereMsoBillingId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|MsoBillingPayment wherePayee($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|MsoBillingPayment wherePosted($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|MsoBillingPayment whereUpdatedAt($value)
 * @mixin \Eloquent
 */
	#[\AllowDynamicProperties]
	class IdeHelperMsoBillingPayment {}
}

namespace App\Models{
/**
 * @property int $id
 * @property int $type
 * @property int $account_id
 * @property int|null $member_id
 * @property string $payee
 * @property numeric $amount
 * @property \Carbon\CarbonImmutable|null $created_at
 * @property \Carbon\CarbonImmutable|null $updated_at
 * @method static \Illuminate\Database\Eloquent\Builder<static>|MsoSubscription newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|MsoSubscription newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|MsoSubscription query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|MsoSubscription whereAccountId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|MsoSubscription whereAmount($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|MsoSubscription whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|MsoSubscription whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|MsoSubscription whereMemberId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|MsoSubscription wherePayee($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|MsoSubscription whereType($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|MsoSubscription whereUpdatedAt($value)
 * @mixin \Eloquent
 */
	#[\AllowDynamicProperties]
	class IdeHelperMsoSubscription {}
}

namespace App\Models{
/**
 * @property int $id
 * @property int|null $province_id
 * @property string|null $name
 * @property-read \App\Models\Province|null $province
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Municipality newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Municipality newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Municipality query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Municipality whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Municipality whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Municipality whereProvinceId($value)
 * @mixin \Eloquent
 */
	#[\AllowDynamicProperties]
	class IdeHelperMunicipality {}
}

namespace App\Models{
/**
 * @property int $id
 * @property string $name
 * @property \Carbon\CarbonImmutable|null $created_at
 * @property \Carbon\CarbonImmutable|null $updated_at
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Occupation newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Occupation newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Occupation query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Occupation whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Occupation whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Occupation whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Occupation whereUpdatedAt($value)
 * @mixin \Eloquent
 */
	#[\AllowDynamicProperties]
	class IdeHelperOccupation {}
}

namespace App\Models{
/**
 * @property int $id
 * @property string $year
 * @property array<array-key, mixed> $officers
 * @property \Carbon\CarbonImmutable|null $created_at
 * @property \Carbon\CarbonImmutable|null $updated_at
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Member> $members
 * @property-read int|null $members_count
 * @method static \Illuminate\Database\Eloquent\Builder<static>|OfficersList newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|OfficersList newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|OfficersList query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|OfficersList whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|OfficersList whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|OfficersList whereOfficers($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|OfficersList whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|OfficersList whereYear($value)
 * @mixin \Eloquent
 */
	#[\AllowDynamicProperties]
	class IdeHelperOfficersList {}
}

namespace App\Models{
/**
 * @property int $id
 * @property string|null $mpc_code
 * @property array<array-key, mixed> $member_ids
 * @property int $is_organization
 * @property int|null $member_type_id
 * @property int|null $member_subtype_id
 * @property int|null $division_id
 * @property string|null $section
 * @property string|null $grade
 * @property int|null $user_id
 * @property string $first_name
 * @property string|null $last_name
 * @property string|null $middle_name
 * @property string|null $middle_initial
 * @property string|null $alt_full_name
 * @property string|null $full_name
 * @property string|null $tin
 * @property int|null $gender_id
 * @property int|null $civil_status_id
 * @property int $patronage_status_id
 * @property \Carbon\CarbonImmutable $membership_date
 * @property string|null $contact
 * @property \Carbon\CarbonImmutable|null $dob
 * @property string|null $place_of_birth
 * @property string|null $address
 * @property int|null $occupation_id
 * @property string|null $occupation_description
 * @property string|null $present_employer
 * @property string|null $highest_educational_attainment
 * @property \Spatie\LaravelData\DataCollection $dependents
 * @property int|null $dependents_count
 * @property int|null $religion_id
 * @property numeric|null $annual_income
 * @property array<array-key, mixed>|null $other_income_sources
 * @property int|null $region_id
 * @property int|null $province_id
 * @property int|null $municipality_id
 * @property int|null $barangay_id
 * @property string|null $terminated_at
 * @property \Carbon\CarbonImmutable|null $created_at
 * @property \Carbon\CarbonImmutable|null $updated_at
 * @property-read \Staudenmeir\LaravelAdjacencyList\Eloquent\Collection<int, \App\Models\Account> $accounts
 * @property-read int|null $accounts_count
 * @property-read \App\Models\CapitalSubscription|null $active_capital_subscription
 * @property-read \App\Models\Barangay|null $barangay
 * @property-read \App\Models\Account|null $capital_subscription_account
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\CapitalSubscriptionPayment> $capital_subscription_payments
 * @property-read int|null $capital_subscription_payments_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\CapitalSubscription> $capital_subscriptions
 * @property-read int|null $capital_subscriptions_count
 * @property-read \App\Models\CivilStatus|null $civil_status
 * @property-read \App\Models\Division|null $division
 * @property-read \App\Models\Account|null $existing_capital_subscription_account
 * @property-read \App\Models\Gender|null $gender
 * @property-read mixed $age
 * @property-read mixed $members
 * @property-read \App\Models\ImprestAccount|null $imprest_account
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Imprest> $imprests
 * @property-read int|null $imprests_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Imprest> $imprests_no_interest
 * @property-read int|null $imprests_no_interest_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Imprest> $imprests_unaccrued
 * @property-read int|null $imprests_unaccrued_count
 * @property-read \App\Models\CapitalSubscription|null $initial_capital_subscription
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\LoanApplication> $loan_applications
 * @property-read int|null $loan_applications_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Loan> $loans
 * @property-read int|null $loans_count
 * @property-read \App\Models\LoveGiftAccount|null $love_gift_account
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\LoveGift> $love_gifts
 * @property-read int|null $love_gifts_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\LoveGift> $love_gifts_no_interest
 * @property-read int|null $love_gifts_no_interest_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\LoveGift> $love_gifts_unaccrued
 * @property-read int|null $love_gifts_unaccrued_count
 * @property-read \Spatie\MediaLibrary\MediaCollections\Models\Collections\MediaCollection<int, \Spatie\MediaLibrary\MediaCollections\Models\Media> $media
 * @property-read int|null $media_count
 * @property-read \App\Models\MemberSubtype|null $member_subtype
 * @property-read \App\Models\MemberType|null $member_type
 * @property-read \App\Models\MembershipStatus|null $membership_acceptance
 * @property-read \App\Models\MembershipStatus|null $membership_termination
 * @property-read \App\Models\Municipality|null $municipality
 * @property-read \App\Models\Occupation|null $occupation
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\OfficersList> $officers_list
 * @property-read int|null $officers_list_count
 * @property-read \App\Models\PatronageStatus $patronage_status
 * @property-read \App\Models\Province|null $province
 * @property-read \App\Models\Region|null $region
 * @property-read \App\Models\Religion|null $religion
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Saving> $savings
 * @property-read int|null $savings_count
 * @property-read \Staudenmeir\LaravelAdjacencyList\Eloquent\Collection<int, \App\Models\SavingsAccount> $savings_accounts
 * @property-read int|null $savings_accounts_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Saving> $savings_no_interest
 * @property-read int|null $savings_no_interest_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Saving> $savings_unaccrued
 * @property-read int|null $savings_unaccrued_count
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Organization newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Organization newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Organization query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Organization whereAddress($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Organization whereAltFullName($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Organization whereAnnualIncome($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Organization whereBarangayId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Organization whereCivilStatusId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Organization whereContact($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Organization whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Organization whereDependents($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Organization whereDependentsCount($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Organization whereDivisionId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Organization whereDob($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Organization whereFirstName($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Organization whereFullName($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Organization whereGenderId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Organization whereGrade($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Organization whereHighestEducationalAttainment($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Organization whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Organization whereIsOrganization($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Organization whereLastName($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Organization whereMemberIds($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Organization whereMemberSubtypeId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Organization whereMemberTypeId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Organization whereMembershipDate($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Organization whereMiddleInitial($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Organization whereMiddleName($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Organization whereMpcCode($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Organization whereMunicipalityId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Organization whereOccupationDescription($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Organization whereOccupationId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Organization whereOtherIncomeSources($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Organization wherePatronageStatusId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Organization wherePlaceOfBirth($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Organization wherePresentEmployer($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Organization whereProvinceId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Organization whereRegionId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Organization whereReligionId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Organization whereSection($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Organization whereTerminatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Organization whereTin($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Organization whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Organization whereUserId($value)
 * @mixin \Eloquent
 */
	#[\AllowDynamicProperties]
	class IdeHelperOrganization {}
}

namespace App\Models{
/**
 * @property int $id
 * @property string $name
 * @property \Carbon\CarbonImmutable|null $created_at
 * @property \Carbon\CarbonImmutable|null $updated_at
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PatronageStatus newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PatronageStatus newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PatronageStatus query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PatronageStatus whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PatronageStatus whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PatronageStatus whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PatronageStatus whereUpdatedAt($value)
 * @mixin \Eloquent
 */
	#[\AllowDynamicProperties]
	class IdeHelperPatronageStatus {}
}

namespace App\Models{
/**
 * @property int $id
 * @property string $name
 * @property \Carbon\CarbonImmutable|null $created_at
 * @property \Carbon\CarbonImmutable|null $updated_at
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PaymentType newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PaymentType newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PaymentType query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PaymentType whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PaymentType whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PaymentType whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PaymentType whereUpdatedAt($value)
 * @mixin \Eloquent
 */
	#[\AllowDynamicProperties]
	class IdeHelperPaymentType {}
}

namespace App\Models{
/**
 * @property int $id
 * @property string $name
 * @property \Carbon\CarbonImmutable|null $created_at
 * @property \Carbon\CarbonImmutable|null $updated_at
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Position newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Position newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Position query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Position whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Position whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Position whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Position whereUpdatedAt($value)
 * @mixin \Eloquent
 */
	#[\AllowDynamicProperties]
	class IdeHelperPosition {}
}

namespace App\Models{
/**
 * @property int $id
 * @property int $region_id
 * @property string $name
 * @property-read \App\Models\Region $region
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Province newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Province newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Province query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Province whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Province whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Province whereRegionId($value)
 * @mixin \Eloquent
 */
	#[\AllowDynamicProperties]
	class IdeHelperProvince {}
}

namespace App\Models{
/**
 * @property int $id
 * @property string $name
 * @property string $description
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Region newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Region newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Region query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Region whereDescription($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Region whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Region whereName($value)
 * @mixin \Eloquent
 */
	#[\AllowDynamicProperties]
	class IdeHelperRegion {}
}

namespace App\Models{
/**
 * @property int $id
 * @property string $name
 * @property \Carbon\CarbonImmutable|null $created_at
 * @property \Carbon\CarbonImmutable|null $updated_at
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Religion newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Religion newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Religion query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Religion whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Religion whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Religion whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Religion whereUpdatedAt($value)
 * @mixin \Eloquent
 */
	#[\AllowDynamicProperties]
	class IdeHelperReligion {}
}

namespace App\Models{
/**
 * @property int $id
 * @property numeric|null $deposit
 * @property numeric|null $withdrawal
 * @property string $reference_number
 * @property \Carbon\CarbonImmutable $transaction_date
 * @property int $cashier_id
 * @property string|null $withdrawable_type
 * @property int|null $withdrawable_id
 * @property \Carbon\CarbonImmutable|null $deleted_at
 * @property \Carbon\CarbonImmutable|null $created_at
 * @property \Carbon\CarbonImmutable|null $updated_at
 * @property-read \App\Models\User $cashier
 * @property-read \Illuminate\Database\Eloquent\Model|\Eloquent|null $withdrawable
 * @method static \Illuminate\Database\Eloquent\Builder<static>|RevolvingFund newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|RevolvingFund newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|RevolvingFund onlyTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|RevolvingFund query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|RevolvingFund whereCashierId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|RevolvingFund whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|RevolvingFund whereDeletedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|RevolvingFund whereDeposit($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|RevolvingFund whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|RevolvingFund whereReferenceNumber($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|RevolvingFund whereTransactionDate($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|RevolvingFund whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|RevolvingFund whereWithdrawableId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|RevolvingFund whereWithdrawableType($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|RevolvingFund whereWithdrawal($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|RevolvingFund withTrashed(bool $withTrashed = true)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|RevolvingFund withoutTrashed()
 * @mixin \Eloquent
 */
	#[\AllowDynamicProperties]
	class IdeHelperRevolvingFund {}
}

namespace App\Models{
/**
 * @property int $id
 * @property int $savings_account_id
 * @property int $member_id
 * @property int $payment_type_id
 * @property string $reference_number
 * @property numeric $amount
 * @property numeric|null $deposit
 * @property numeric|null $withdrawal
 * @property numeric $interest_rate
 * @property numeric $interest
 * @property \Carbon\CarbonImmutable $transaction_date
 * @property \Carbon\CarbonImmutable|null $transaction_datetime
 * @property numeric $balance
 * @property \Carbon\CarbonImmutable|null $interest_date
 * @property bool $accrued
 * @property int|null $cashier_id
 * @property \Carbon\CarbonImmutable|null $created_at
 * @property \Carbon\CarbonImmutable|null $updated_at
 * @property-read \App\Models\User|null $cashier
 * @property-read \App\Models\Member $member
 * @property-read \App\Models\RevolvingFund|null $revolving_fund
 * @property-read \App\Models\SavingsAccount $savings_account
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Saving newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Saving newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Saving query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Saving whereAccrued($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Saving whereAmount($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Saving whereBalance($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Saving whereCashierId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Saving whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Saving whereDeposit($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Saving whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Saving whereInterest($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Saving whereInterestDate($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Saving whereInterestRate($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Saving whereMemberId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Saving wherePaymentTypeId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Saving whereReferenceNumber($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Saving whereSavingsAccountId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Saving whereTransactionDate($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Saving whereTransactionDatetime($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Saving whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Saving whereWithdrawal($value)
 * @mixin \Eloquent
 */
	#[\AllowDynamicProperties]
	class IdeHelperSaving {}
}

namespace App\Models{
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
 * @property-read \App\Models\AccountType $account_type
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
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Transaction> $transactions
 * @property-read int|null $transactions_count
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
	#[\AllowDynamicProperties]
	class IdeHelperSavingsAccount {}
}

namespace App\Models{
/**
 * @property int $id
 * @property string $name
 * @property \Carbon\CarbonImmutable|null $created_at
 * @property \Carbon\CarbonImmutable|null $updated_at
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\SignatureSetSignatory> $signatories
 * @property-read int|null $signatories_count
 * @method static \Illuminate\Database\Eloquent\Builder<static>|SignatureSet newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|SignatureSet newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|SignatureSet query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|SignatureSet whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|SignatureSet whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|SignatureSet whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|SignatureSet whereUpdatedAt($value)
 * @mixin \Eloquent
 */
	#[\AllowDynamicProperties]
	class IdeHelperSignatureSet {}
}

namespace App\Models{
/**
 * @property int $id
 * @property int $signature_set_id
 * @property string $action
 * @property int $user_id
 * @property string $designation
 * @property \Carbon\CarbonImmutable|null $created_at
 * @property \Carbon\CarbonImmutable|null $updated_at
 * @property-read \App\Models\User $user
 * @method static \Illuminate\Database\Eloquent\Builder<static>|SignatureSetSignatory newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|SignatureSetSignatory newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|SignatureSetSignatory query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|SignatureSetSignatory whereAction($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|SignatureSetSignatory whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|SignatureSetSignatory whereDesignation($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|SignatureSetSignatory whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|SignatureSetSignatory whereSignatureSetId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|SignatureSetSignatory whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|SignatureSetSignatory whereUserId($value)
 * @mixin \Eloquent
 */
	#[\AllowDynamicProperties]
	class IdeHelperSignatureSetSignatory {}
}

namespace App\Models{
/**
 * @property int $id
 * @property string $name
 * @property array<array-key, mixed> $content
 * @property \Carbon\CarbonImmutable|null $created_at
 * @property \Carbon\CarbonImmutable|null $updated_at
 * @method static \Illuminate\Database\Eloquent\Builder<static>|SystemConfiguration newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|SystemConfiguration newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|SystemConfiguration query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|SystemConfiguration whereContent($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|SystemConfiguration whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|SystemConfiguration whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|SystemConfiguration whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|SystemConfiguration whereUpdatedAt($value)
 * @mixin \Eloquent
 */
	#[\AllowDynamicProperties]
	class IdeHelperSystemConfiguration {}
}

namespace App\Models{
/**
 * @property int $id
 * @property int $member_id
 * @property int $payment_type_id
 * @property string $reference_number
 * @property string|null $identifier
 * @property \Carbon\CarbonImmutable $maturity_date
 * @property string|null $withdrawal_date
 * @property numeric $amount
 * @property int $number_of_days
 * @property numeric $maturity_amount
 * @property numeric $interest_rate
 * @property numeric|null $interest
 * @property \Carbon\CarbonImmutable $transaction_date
 * @property string $tdc_number
 * @property string $time_deposit_account_id
 * @property int|null $cashier_id
 * @property \Carbon\CarbonImmutable|null $created_at
 * @property \Carbon\CarbonImmutable|null $updated_at
 * @property-read mixed $accrued_interest
 * @property-read mixed $amount_in_words
 * @property-read \App\Models\User|null $cashier
 * @property-read mixed $days_in_words
 * @property-read mixed $interest_earned
 * @property-read mixed $interest_rate_in_words
 * @property-read \App\Models\Member $member
 * @property-read \App\Models\TimeDepositAccount|null $time_deposit_account
 * @method static \Illuminate\Database\Eloquent\Builder<static>|TimeDeposit newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|TimeDeposit newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|TimeDeposit query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|TimeDeposit whereAmount($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|TimeDeposit whereCashierId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|TimeDeposit whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|TimeDeposit whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|TimeDeposit whereIdentifier($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|TimeDeposit whereInterest($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|TimeDeposit whereInterestRate($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|TimeDeposit whereMaturityAmount($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|TimeDeposit whereMaturityDate($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|TimeDeposit whereMemberId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|TimeDeposit whereNumberOfDays($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|TimeDeposit wherePaymentTypeId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|TimeDeposit whereReferenceNumber($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|TimeDeposit whereTdcNumber($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|TimeDeposit whereTimeDepositAccountId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|TimeDeposit whereTransactionDate($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|TimeDeposit whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|TimeDeposit whereWithdrawalDate($value)
 * @mixin \Eloquent
 */
	#[\AllowDynamicProperties]
	class IdeHelperTimeDeposit {}
}

namespace App\Models{
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
 * @property-read \App\Models\AccountType $account_type
 * @property-read \Staudenmeir\LaravelAdjacencyList\Eloquent\Collection<int, \App\Models\TimeDepositAccount> $children
 * @property-read int|null $children_count
 * @property-read \App\Models\Member|null $member
 * @property-read \App\Models\TimeDeposit|null $original_time_deposit
 * @property-read \App\Models\TimeDepositAccount|null $parent
 * @property-read \App\Models\TimeDeposit|null $time_deposit
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\TimeDeposit> $time_deposits
 * @property-read int|null $time_deposits_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Transaction> $transactions
 * @property-read int|null $transactions_count
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
	#[\AllowDynamicProperties]
	class IdeHelperTimeDepositAccount {}
}

namespace App\Models{
/**
 * @property int $id
 * @property int $transaction_type_id
 * @property int $account_id
 * @property int|null $member_id
 * @property int|null $payment_type_id
 * @property string $reference_number
 * @property string $payee
 * @property string|null $remarks
 * @property numeric|null $credit
 * @property numeric|null $debit
 * @property \Carbon\CarbonImmutable $transaction_date
 * @property string|null $tag
 * @property int|null $from_billing_type
 * @property \Carbon\CarbonImmutable|null $created_at
 * @property \Carbon\CarbonImmutable|null $updated_at
 * @property-read \App\Models\Account $account
 * @property-read \App\Models\Member|null $member
 * @property-read \App\Models\TransactionType $transaction_type
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Transaction newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Transaction newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Transaction query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Transaction whereAccountId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Transaction whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Transaction whereCredit($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Transaction whereDebit($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Transaction whereFromBillingType($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Transaction whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Transaction whereMemberId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Transaction wherePayee($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Transaction wherePaymentTypeId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Transaction whereReferenceNumber($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Transaction whereRemarks($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Transaction whereTag($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Transaction whereTransactionDate($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Transaction whereTransactionTypeId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Transaction whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Transaction withoutCashEquivalents()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Transaction withoutMso()
 * @mixin \Eloquent
 */
	#[\AllowDynamicProperties]
	class IdeHelperTransaction {}
}

namespace App\Models{
/**
 * @property int $id
 * @property \Carbon\CarbonImmutable $date
 * @property int $is_current
 * @property \Carbon\CarbonImmutable|null $created_at
 * @property \Carbon\CarbonImmutable|null $updated_at
 * @method static \Illuminate\Database\Eloquent\Builder<static>|TransactionDateHistory newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|TransactionDateHistory newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|TransactionDateHistory query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|TransactionDateHistory whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|TransactionDateHistory whereDate($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|TransactionDateHistory whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|TransactionDateHistory whereIsCurrent($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|TransactionDateHistory whereUpdatedAt($value)
 * @mixin \Eloquent
 */
	#[\AllowDynamicProperties]
	class IdeHelperTransactionDateHistory {}
}

namespace App\Models{
/**
 * @property int $id
 * @property string $name
 * @property \Carbon\CarbonImmutable|null $created_at
 * @property \Carbon\CarbonImmutable|null $updated_at
 * @method static \Illuminate\Database\Eloquent\Builder<static>|TransactionType newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|TransactionType newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|TransactionType query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|TransactionType whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|TransactionType whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|TransactionType whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|TransactionType whereUpdatedAt($value)
 * @mixin \Eloquent
 */
	#[\AllowDynamicProperties]
	class IdeHelperTransactionType {}
}

namespace App\Models{
/**
 * @property int $id
 * @property string $name
 * @property string $email
 * @property \Carbon\CarbonImmutable|null $email_verified_at
 * @property string $password
 * @property int|null $member_id
 * @property string|null $remember_token
 * @property \Carbon\CarbonImmutable|null $created_at
 * @property \Carbon\CarbonImmutable|null $updated_at
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\CashBeginning> $cashier_cash_beginnings
 * @property-read int|null $cashier_cash_beginnings_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\CashCollectiblePayment> $cashier_cash_collectible_payments
 * @property-read int|null $cashier_cash_collectible_payments_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\CapitalSubscriptionPayment> $cashier_cbu_payments
 * @property-read int|null $cashier_cbu_payments_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Imprest> $cashier_imprests
 * @property-read int|null $cashier_imprests_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\LoanPayment> $cashier_loan_payments
 * @property-read int|null $cashier_loan_payments_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Saving> $cashier_savings
 * @property-read int|null $cashier_savings_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\TimeDeposit> $cashier_time_deposits
 * @property-read int|null $cashier_time_deposits_count
 * @property-read \App\Models\Member|null $member
 * @property-read \Illuminate\Notifications\DatabaseNotificationCollection<int, \Illuminate\Notifications\DatabaseNotification> $notifications
 * @property-read int|null $notifications_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \Spatie\Permission\Models\Permission> $permissions
 * @property-read int|null $permissions_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \Spatie\Permission\Models\Role> $roles
 * @property-read int|null $roles_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \Laravel\Sanctum\PersonalAccessToken> $tokens
 * @property-read int|null $tokens_count
 * @method static \Database\Factories\UserFactory factory($count = null, $state = [])
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User permission($permissions, $without = false)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User role($roles, $guard = null, $without = false)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User whereEmail($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User whereEmailVerifiedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User whereMemberId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User wherePassword($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User whereRememberToken($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User withoutPermission($permissions)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User withoutRole($roles, $guard = null)
 * @mixin \Eloquent
 */
	#[\AllowDynamicProperties]
	class IdeHelperUser {}
}

namespace App\Models{
/**
 * @property int $id
 * @property string $name
 * @property \Carbon\CarbonImmutable|null $created_at
 * @property \Carbon\CarbonImmutable|null $updated_at
 * @method static \Illuminate\Database\Eloquent\Builder<static>|VoucherType newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|VoucherType newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|VoucherType query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|VoucherType whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|VoucherType whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|VoucherType whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|VoucherType whereUpdatedAt($value)
 * @mixin \Eloquent
 */
	#[\AllowDynamicProperties]
	class IdeHelperVoucherType {}
}

