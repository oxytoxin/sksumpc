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
 * App\Models\Account
 *
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
 * @property-read \App\Models\AccountType $account_type
 * @property-read \Staudenmeir\LaravelAdjacencyList\Eloquent\Collection|\App\Models\Account[] $children
 * @property-read int|null $children_count
 * @property-read \App\Models\Account|null $parent
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Transaction> $transactions
 * @property-read int|null $transactions_count
 * @property-read \Staudenmeir\LaravelAdjacencyList\Eloquent\Collection|\App\Models\Account[] $ancestors The model's recursive parents.
 * @property-read int|null $ancestors_count
 * @property-read \Staudenmeir\LaravelAdjacencyList\Eloquent\Collection|\App\Models\Account[] $ancestorsAndSelf The model's recursive parents and itself.
 * @property-read int|null $ancestors_and_self_count
 * @property-read \Staudenmeir\LaravelAdjacencyList\Eloquent\Collection|\App\Models\Account[] $bloodline The model's ancestors, descendants and itself.
 * @property-read int|null $bloodline_count
 * @property-read \Staudenmeir\LaravelAdjacencyList\Eloquent\Collection|\App\Models\Account[] $childrenAndSelf The model's direct children and itself.
 * @property-read int|null $children_and_self_count
 * @property-read \Staudenmeir\LaravelAdjacencyList\Eloquent\Collection|\App\Models\Account[] $descendants The model's recursive children.
 * @property-read int|null $descendants_count
 * @property-read \Staudenmeir\LaravelAdjacencyList\Eloquent\Collection|\App\Models\Account[] $descendantsAndSelf The model's recursive children and itself.
 * @property-read int|null $descendants_and_self_count
 * @property-read \Staudenmeir\LaravelAdjacencyList\Eloquent\Collection|\App\Models\Account[] $parentAndSelf The model's direct parent and itself.
 * @property-read int|null $parent_and_self_count
 * @property-read \App\Models\Account|null $rootAncestor The model's topmost parent.
 * @property-read \Staudenmeir\LaravelAdjacencyList\Eloquent\Collection|\App\Models\Account[] $siblings The parent's other children.
 * @property-read int|null $siblings_count
 * @property-read \Staudenmeir\LaravelAdjacencyList\Eloquent\Collection|\App\Models\Account[] $siblingsAndSelf All the parent's children.
 * @property-read int|null $siblings_and_self_count
 * @method static \Staudenmeir\LaravelAdjacencyList\Eloquent\Collection<int, static> all($columns = ['*'])
 * @method static \Staudenmeir\LaravelAdjacencyList\Eloquent\Builder|Account breadthFirst()
 * @method static \Staudenmeir\LaravelAdjacencyList\Eloquent\Builder|Account depthFirst()
 * @method static \Staudenmeir\LaravelAdjacencyList\Eloquent\Builder|Account doesntHaveChildren()
 * @method static \Staudenmeir\LaravelAdjacencyList\Eloquent\Collection<int, static> get($columns = ['*'])
 * @method static \Staudenmeir\LaravelAdjacencyList\Eloquent\Builder|Account getExpressionGrammar()
 * @method static \Staudenmeir\LaravelAdjacencyList\Eloquent\Builder|Account hasChildren()
 * @method static \Staudenmeir\LaravelAdjacencyList\Eloquent\Builder|Account hasParent()
 * @method static \Staudenmeir\LaravelAdjacencyList\Eloquent\Builder|Account isLeaf()
 * @method static \Staudenmeir\LaravelAdjacencyList\Eloquent\Builder|Account isRoot()
 * @method static \Staudenmeir\LaravelAdjacencyList\Eloquent\Builder|Account newModelQuery()
 * @method static \Staudenmeir\LaravelAdjacencyList\Eloquent\Builder|Account newQuery()
 * @method static \Staudenmeir\LaravelAdjacencyList\Eloquent\Builder|Account query()
 * @method static \Staudenmeir\LaravelAdjacencyList\Eloquent\Builder|Account tree($maxDepth = null)
 * @method static \Staudenmeir\LaravelAdjacencyList\Eloquent\Builder|Account treeOf(\Illuminate\Database\Eloquent\Model|callable $constraint, $maxDepth = null)
 * @method static \Staudenmeir\LaravelAdjacencyList\Eloquent\Builder|Account whereAccountTypeId($value)
 * @method static \Staudenmeir\LaravelAdjacencyList\Eloquent\Builder|Account whereAccountableId($value)
 * @method static \Staudenmeir\LaravelAdjacencyList\Eloquent\Builder|Account whereAccountableType($value)
 * @method static \Staudenmeir\LaravelAdjacencyList\Eloquent\Builder|Account whereCreatedAt($value)
 * @method static \Staudenmeir\LaravelAdjacencyList\Eloquent\Builder|Account whereDepth($operator, $value = null)
 * @method static \Staudenmeir\LaravelAdjacencyList\Eloquent\Builder|Account whereId($value)
 * @method static \Staudenmeir\LaravelAdjacencyList\Eloquent\Builder|Account whereMemberId($value)
 * @method static \Staudenmeir\LaravelAdjacencyList\Eloquent\Builder|Account whereName($value)
 * @method static \Staudenmeir\LaravelAdjacencyList\Eloquent\Builder|Account whereNumber($value)
 * @method static \Staudenmeir\LaravelAdjacencyList\Eloquent\Builder|Account whereParentId($value)
 * @method static \Staudenmeir\LaravelAdjacencyList\Eloquent\Builder|Account whereTag($value)
 * @method static \Staudenmeir\LaravelAdjacencyList\Eloquent\Builder|Account whereUpdatedAt($value)
 * @method static \Staudenmeir\LaravelAdjacencyList\Eloquent\Builder|Account withCode()
 * @method static \Staudenmeir\LaravelAdjacencyList\Eloquent\Builder|Account withGlobalScopes(array $scopes)
 * @method static \Staudenmeir\LaravelAdjacencyList\Eloquent\Builder|Account withRelationshipExpression($direction, callable $constraint, $initialDepth, $from = null, $maxDepth = null)
 * @mixin \Eloquent
 */
	class IdeHelperAccount {}
}

namespace App\Models{
/**
 * App\Models\AccountType
 *
 * @property int $id
 * @property string $name
 * @property int $debit_operator
 * @property int $credit_operator
 * @property \Carbon\CarbonImmutable|null $created_at
 * @property \Carbon\CarbonImmutable|null $updated_at
 * @property-read \Staudenmeir\LaravelAdjacencyList\Eloquent\Collection<int, \App\Models\Account> $accounts
 * @property-read int|null $accounts_count
 * @method static \Illuminate\Database\Eloquent\Builder|AccountType newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|AccountType newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|AccountType query()
 * @method static \Illuminate\Database\Eloquent\Builder|AccountType whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|AccountType whereCreditOperator($value)
 * @method static \Illuminate\Database\Eloquent\Builder|AccountType whereDebitOperator($value)
 * @method static \Illuminate\Database\Eloquent\Builder|AccountType whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|AccountType whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|AccountType whereUpdatedAt($value)
 * @mixin \Eloquent
 */
	class IdeHelperAccountType {}
}

namespace App\Models{
/**
 * App\Models\BalanceForwardedEntry
 *
 * @property int $id
 * @property int $balance_forwarded_summary_id
 * @property int $account_id
 * @property string|null $credit
 * @property string|null $debit
 * @property \Carbon\CarbonImmutable|null $created_at
 * @property \Carbon\CarbonImmutable|null $updated_at
 * @property-read \App\Models\Account $account
 * @property-read \App\Models\BalanceForwardedSummary $balance_forwarded_summary
 * @method static \Illuminate\Database\Eloquent\Builder|BalanceForwardedEntry newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|BalanceForwardedEntry newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|BalanceForwardedEntry query()
 * @method static \Illuminate\Database\Eloquent\Builder|BalanceForwardedEntry whereAccountId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|BalanceForwardedEntry whereBalanceForwardedSummaryId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|BalanceForwardedEntry whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|BalanceForwardedEntry whereCredit($value)
 * @method static \Illuminate\Database\Eloquent\Builder|BalanceForwardedEntry whereDebit($value)
 * @method static \Illuminate\Database\Eloquent\Builder|BalanceForwardedEntry whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|BalanceForwardedEntry whereUpdatedAt($value)
 * @mixin \Eloquent
 */
	class IdeHelperBalanceForwardedEntry {}
}

namespace App\Models{
/**
 * App\Models\BalanceForwardedSummary
 *
 * @property int $id
 * @property \Carbon\CarbonImmutable $generated_date
 * @property \Carbon\CarbonImmutable|null $created_at
 * @property \Carbon\CarbonImmutable|null $updated_at
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\BalanceForwardedEntry> $balance_forwarded_entries
 * @property-read int|null $balance_forwarded_entries_count
 * @method static \Illuminate\Database\Eloquent\Builder|BalanceForwardedSummary newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|BalanceForwardedSummary newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|BalanceForwardedSummary query()
 * @method static \Illuminate\Database\Eloquent\Builder|BalanceForwardedSummary whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|BalanceForwardedSummary whereGeneratedDate($value)
 * @method static \Illuminate\Database\Eloquent\Builder|BalanceForwardedSummary whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|BalanceForwardedSummary whereUpdatedAt($value)
 * @mixin \Eloquent
 */
	class IdeHelperBalanceForwardedSummary {}
}

namespace App\Models{
/**
 * App\Models\Barangay
 *
 * @property int $id
 * @property int $municipality_id
 * @property string $name
 * @property-read \App\Models\Municipality $municipality
 * @method static \Illuminate\Database\Eloquent\Builder|Barangay newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Barangay newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Barangay query()
 * @method static \Illuminate\Database\Eloquent\Builder|Barangay whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Barangay whereMunicipalityId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Barangay whereName($value)
 * @mixin \Eloquent
 */
	class IdeHelperBarangay {}
}

namespace App\Models{
/**
 * App\Models\CapitalSubscription
 *
 * @property int $id
 * @property int $member_id
 * @property bool $is_common
 * @property string|null $code
 * @property int $number_of_terms
 * @property string $number_of_shares
 * @property string $amount_subscribed
 * @property string|null $monthly_payment
 * @property string|null $initial_amount_paid
 * @property string $par_value
 * @property string $outstanding_balance
 * @property \Carbon\CarbonImmutable $transaction_date
 * @property \Carbon\CarbonImmutable|null $created_at
 * @property \Carbon\CarbonImmutable|null $updated_at
 * @property-read \App\Models\CapitalSubscriptionAmortization|null $active_capital_subscription_amortization
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\CapitalSubscriptionAmortization> $capital_subscription_amortizations
 * @property-read int|null $capital_subscription_amortizations_count
 * @property-read \App\Models\Member $member
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\CapitalSubscriptionAmortization> $paid_capital_subscription_amortizations
 * @property-read int|null $paid_capital_subscription_amortizations_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\CapitalSubscriptionPayment> $payments
 * @property-read int|null $payments_count
 * @method static \Illuminate\Database\Eloquent\Builder|CapitalSubscription newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|CapitalSubscription newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|CapitalSubscription query()
 * @method static \Illuminate\Database\Eloquent\Builder|CapitalSubscription whereAmountSubscribed($value)
 * @method static \Illuminate\Database\Eloquent\Builder|CapitalSubscription whereCode($value)
 * @method static \Illuminate\Database\Eloquent\Builder|CapitalSubscription whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|CapitalSubscription whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|CapitalSubscription whereInitialAmountPaid($value)
 * @method static \Illuminate\Database\Eloquent\Builder|CapitalSubscription whereIsCommon($value)
 * @method static \Illuminate\Database\Eloquent\Builder|CapitalSubscription whereMemberId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|CapitalSubscription whereMonthlyPayment($value)
 * @method static \Illuminate\Database\Eloquent\Builder|CapitalSubscription whereNumberOfShares($value)
 * @method static \Illuminate\Database\Eloquent\Builder|CapitalSubscription whereNumberOfTerms($value)
 * @method static \Illuminate\Database\Eloquent\Builder|CapitalSubscription whereOutstandingBalance($value)
 * @method static \Illuminate\Database\Eloquent\Builder|CapitalSubscription whereParValue($value)
 * @method static \Illuminate\Database\Eloquent\Builder|CapitalSubscription whereTransactionDate($value)
 * @method static \Illuminate\Database\Eloquent\Builder|CapitalSubscription whereUpdatedAt($value)
 * @mixin \Eloquent
 */
	class IdeHelperCapitalSubscription {}
}

namespace App\Models{
/**
 * App\Models\CapitalSubscriptionAmortization
 *
 * @property int $id
 * @property int $capital_subscription_id
 * @property string|null $billable_date
 * @property \Carbon\CarbonImmutable $due_date
 * @property int $term
 * @property string|null $amount
 * @property string|null $amount_paid
 * @property string|null $arrears
 * @property string|null $remarks
 * @property \Carbon\CarbonImmutable|null $created_at
 * @property \Carbon\CarbonImmutable|null $updated_at
 * @property-read \App\Models\CapitalSubscription $capital_subscription
 * @method static \Illuminate\Database\Eloquent\Builder|CapitalSubscriptionAmortization newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|CapitalSubscriptionAmortization newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|CapitalSubscriptionAmortization query()
 * @method static \Illuminate\Database\Eloquent\Builder|CapitalSubscriptionAmortization whereAmount($value)
 * @method static \Illuminate\Database\Eloquent\Builder|CapitalSubscriptionAmortization whereAmountPaid($value)
 * @method static \Illuminate\Database\Eloquent\Builder|CapitalSubscriptionAmortization whereArrears($value)
 * @method static \Illuminate\Database\Eloquent\Builder|CapitalSubscriptionAmortization whereBillableDate($value)
 * @method static \Illuminate\Database\Eloquent\Builder|CapitalSubscriptionAmortization whereCapitalSubscriptionId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|CapitalSubscriptionAmortization whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|CapitalSubscriptionAmortization whereDueDate($value)
 * @method static \Illuminate\Database\Eloquent\Builder|CapitalSubscriptionAmortization whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|CapitalSubscriptionAmortization whereRemarks($value)
 * @method static \Illuminate\Database\Eloquent\Builder|CapitalSubscriptionAmortization whereTerm($value)
 * @method static \Illuminate\Database\Eloquent\Builder|CapitalSubscriptionAmortization whereUpdatedAt($value)
 * @mixin \Eloquent
 */
	class IdeHelperCapitalSubscriptionAmortization {}
}

namespace App\Models{
/**
 * App\Models\CapitalSubscriptionBilling
 *
 * @property int $id
 * @property \Carbon\CarbonImmutable $date
 * @property string|null $billable_date
 * @property int|null $payment_type_id
 * @property string|null $reference_number
 * @property int|null $cashier_id
 * @property bool $posted
 * @property \Carbon\CarbonImmutable|null $created_at
 * @property \Carbon\CarbonImmutable|null $updated_at
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\CapitalSubscriptionBillingPayment> $capital_subscription_billing_payments
 * @property-read int|null $capital_subscription_billing_payments_count
 * @property-read \App\Models\User|null $cashier
 * @method static \Illuminate\Database\Eloquent\Builder|CapitalSubscriptionBilling newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|CapitalSubscriptionBilling newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|CapitalSubscriptionBilling query()
 * @method static \Illuminate\Database\Eloquent\Builder|CapitalSubscriptionBilling whereBillableDate($value)
 * @method static \Illuminate\Database\Eloquent\Builder|CapitalSubscriptionBilling whereCashierId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|CapitalSubscriptionBilling whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|CapitalSubscriptionBilling whereDate($value)
 * @method static \Illuminate\Database\Eloquent\Builder|CapitalSubscriptionBilling whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|CapitalSubscriptionBilling wherePaymentTypeId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|CapitalSubscriptionBilling wherePosted($value)
 * @method static \Illuminate\Database\Eloquent\Builder|CapitalSubscriptionBilling whereReferenceNumber($value)
 * @method static \Illuminate\Database\Eloquent\Builder|CapitalSubscriptionBilling whereUpdatedAt($value)
 * @mixin \Eloquent
 */
	class IdeHelperCapitalSubscriptionBilling {}
}

namespace App\Models{
/**
 * App\Models\CapitalSubscriptionBillingPayment
 *
 * @property int $id
 * @property int $capital_subscription_billing_id
 * @property int $member_id
 * @property int $capital_subscription_amortization_id
 * @property string $amount_due
 * @property string $amount_paid
 * @property int $posted
 * @property \Carbon\CarbonImmutable|null $created_at
 * @property \Carbon\CarbonImmutable|null $updated_at
 * @property-read \App\Models\CapitalSubscriptionAmortization $capital_subscription_amortization
 * @method static \Illuminate\Database\Eloquent\Builder|CapitalSubscriptionBillingPayment newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|CapitalSubscriptionBillingPayment newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|CapitalSubscriptionBillingPayment query()
 * @method static \Illuminate\Database\Eloquent\Builder|CapitalSubscriptionBillingPayment whereAmountDue($value)
 * @method static \Illuminate\Database\Eloquent\Builder|CapitalSubscriptionBillingPayment whereAmountPaid($value)
 * @method static \Illuminate\Database\Eloquent\Builder|CapitalSubscriptionBillingPayment whereCapitalSubscriptionAmortizationId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|CapitalSubscriptionBillingPayment whereCapitalSubscriptionBillingId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|CapitalSubscriptionBillingPayment whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|CapitalSubscriptionBillingPayment whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|CapitalSubscriptionBillingPayment whereMemberId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|CapitalSubscriptionBillingPayment wherePosted($value)
 * @method static \Illuminate\Database\Eloquent\Builder|CapitalSubscriptionBillingPayment whereUpdatedAt($value)
 * @mixin \Eloquent
 */
	class IdeHelperCapitalSubscriptionBillingPayment {}
}

namespace App\Models{
/**
 * App\Models\CapitalSubscriptionPayment
 *
 * @property int $id
 * @property int $capital_subscription_id
 * @property int $payment_type_id
 * @property string $amount
 * @property string|null $deposit
 * @property string|null $withdrawal
 * @property string $running_balance
 * @property string $reference_number
 * @property string|null $remarks
 * @property \Carbon\CarbonImmutable $transaction_date
 * @property int|null $cashier_id
 * @property \Carbon\CarbonImmutable|null $created_at
 * @property \Carbon\CarbonImmutable|null $updated_at
 * @property-read \App\Models\CapitalSubscription $capital_subscription
 * @property-read \App\Models\User|null $cashier
 * @method static \Illuminate\Database\Eloquent\Builder|CapitalSubscriptionPayment newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|CapitalSubscriptionPayment newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|CapitalSubscriptionPayment query()
 * @method static \Illuminate\Database\Eloquent\Builder|CapitalSubscriptionPayment whereAmount($value)
 * @method static \Illuminate\Database\Eloquent\Builder|CapitalSubscriptionPayment whereCapitalSubscriptionId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|CapitalSubscriptionPayment whereCashierId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|CapitalSubscriptionPayment whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|CapitalSubscriptionPayment whereDeposit($value)
 * @method static \Illuminate\Database\Eloquent\Builder|CapitalSubscriptionPayment whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|CapitalSubscriptionPayment wherePaymentTypeId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|CapitalSubscriptionPayment whereReferenceNumber($value)
 * @method static \Illuminate\Database\Eloquent\Builder|CapitalSubscriptionPayment whereRemarks($value)
 * @method static \Illuminate\Database\Eloquent\Builder|CapitalSubscriptionPayment whereRunningBalance($value)
 * @method static \Illuminate\Database\Eloquent\Builder|CapitalSubscriptionPayment whereTransactionDate($value)
 * @method static \Illuminate\Database\Eloquent\Builder|CapitalSubscriptionPayment whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|CapitalSubscriptionPayment whereWithdrawal($value)
 * @mixin \Eloquent
 */
	class IdeHelperCapitalSubscriptionPayment {}
}

namespace App\Models{
/**
 * App\Models\CashBeginning
 *
 * @property int $id
 * @property string $amount
 * @property \Carbon\CarbonImmutable $transaction_date
 * @property int|null $cashier_id
 * @property \Carbon\CarbonImmutable|null $created_at
 * @property \Carbon\CarbonImmutable|null $updated_at
 * @property-read \App\Models\User|null $cashier
 * @method static \Illuminate\Database\Eloquent\Builder|CashBeginning newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|CashBeginning newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|CashBeginning query()
 * @method static \Illuminate\Database\Eloquent\Builder|CashBeginning whereAmount($value)
 * @method static \Illuminate\Database\Eloquent\Builder|CashBeginning whereCashierId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|CashBeginning whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|CashBeginning whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|CashBeginning whereTransactionDate($value)
 * @method static \Illuminate\Database\Eloquent\Builder|CashBeginning whereUpdatedAt($value)
 * @mixin \Eloquent
 */
	class IdeHelperCashBeginning {}
}

namespace App\Models{
/**
 * App\Models\CashCollectible
 *
 * @property int $id
 * @property int $cash_collectible_category_id
 * @property string $name
 * @property bool $has_inventory
 * @property \Carbon\CarbonImmutable|null $created_at
 * @property \Carbon\CarbonImmutable|null $updated_at
 * @property-read \App\Models\CashCollectibleCategory $cash_collectible_category
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\CashCollectiblePayment> $payments
 * @property-read int|null $payments_count
 * @method static \Illuminate\Database\Eloquent\Builder|CashCollectible newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|CashCollectible newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|CashCollectible query()
 * @method static \Illuminate\Database\Eloquent\Builder|CashCollectible whereCashCollectibleCategoryId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|CashCollectible whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|CashCollectible whereHasInventory($value)
 * @method static \Illuminate\Database\Eloquent\Builder|CashCollectible whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|CashCollectible whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|CashCollectible whereUpdatedAt($value)
 * @mixin \Eloquent
 */
	class IdeHelperCashCollectible {}
}

namespace App\Models{
/**
 * App\Models\CashCollectibleCategory
 *
 * @property int $id
 * @property string $name
 * @property \Carbon\CarbonImmutable|null $created_at
 * @property \Carbon\CarbonImmutable|null $updated_at
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\CashCollectible> $cash_collectibles
 * @property-read int|null $cash_collectibles_count
 * @method static \Illuminate\Database\Eloquent\Builder|CashCollectibleCategory newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|CashCollectibleCategory newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|CashCollectibleCategory query()
 * @method static \Illuminate\Database\Eloquent\Builder|CashCollectibleCategory whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|CashCollectibleCategory whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|CashCollectibleCategory whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|CashCollectibleCategory whereUpdatedAt($value)
 * @mixin \Eloquent
 */
	class IdeHelperCashCollectibleCategory {}
}

namespace App\Models{
/**
 * App\Models\CashCollectiblePayment
 *
 * @property int $id
 * @property int $cash_collectible_id
 * @property int|null $member_id
 * @property string $payee
 * @property int $payment_type_id
 * @property string $reference_number
 * @property string $amount
 * @property \Carbon\CarbonImmutable $transaction_date
 * @property int|null $cashier_id
 * @property \Carbon\CarbonImmutable|null $created_at
 * @property \Carbon\CarbonImmutable|null $updated_at
 * @property-read \App\Models\CashCollectible $cash_collectible
 * @property-read \App\Models\User|null $cashier
 * @property-read \App\Models\Member|null $member
 * @method static \Illuminate\Database\Eloquent\Builder|CashCollectiblePayment newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|CashCollectiblePayment newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|CashCollectiblePayment query()
 * @method static \Illuminate\Database\Eloquent\Builder|CashCollectiblePayment whereAmount($value)
 * @method static \Illuminate\Database\Eloquent\Builder|CashCollectiblePayment whereCashCollectibleId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|CashCollectiblePayment whereCashierId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|CashCollectiblePayment whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|CashCollectiblePayment whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|CashCollectiblePayment whereMemberId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|CashCollectiblePayment wherePayee($value)
 * @method static \Illuminate\Database\Eloquent\Builder|CashCollectiblePayment wherePaymentTypeId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|CashCollectiblePayment whereReferenceNumber($value)
 * @method static \Illuminate\Database\Eloquent\Builder|CashCollectiblePayment whereTransactionDate($value)
 * @method static \Illuminate\Database\Eloquent\Builder|CashCollectiblePayment whereUpdatedAt($value)
 * @mixin \Eloquent
 */
	class IdeHelperCashCollectiblePayment {}
}

namespace App\Models{
/**
 * App\Models\CivilStatus
 *
 * @property int $id
 * @property string $name
 * @property \Carbon\CarbonImmutable|null $created_at
 * @property \Carbon\CarbonImmutable|null $updated_at
 * @method static \Illuminate\Database\Eloquent\Builder|CivilStatus newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|CivilStatus newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|CivilStatus query()
 * @method static \Illuminate\Database\Eloquent\Builder|CivilStatus whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|CivilStatus whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|CivilStatus whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|CivilStatus whereUpdatedAt($value)
 * @mixin \Eloquent
 */
	class IdeHelperCivilStatus {}
}

namespace App\Models{
/**
 * App\Models\DisapprovalReason
 *
 * @property int $id
 * @property string $name
 * @property \Carbon\CarbonImmutable|null $created_at
 * @property \Carbon\CarbonImmutable|null $updated_at
 * @method static \Illuminate\Database\Eloquent\Builder|DisapprovalReason newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|DisapprovalReason newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|DisapprovalReason query()
 * @method static \Illuminate\Database\Eloquent\Builder|DisapprovalReason whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|DisapprovalReason whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|DisapprovalReason whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|DisapprovalReason whereUpdatedAt($value)
 * @mixin \Eloquent
 */
	class IdeHelperDisapprovalReason {}
}

namespace App\Models{
/**
 * App\Models\DisbursementVoucher
 *
 * @property int $id
 * @property string $name
 * @property string|null $address
 * @property string $reference_number
 * @property string $description
 * @property \Carbon\CarbonImmutable $transaction_date
 * @property int $bookkeeper_id
 * @property \Carbon\CarbonImmutable|null $created_at
 * @property \Carbon\CarbonImmutable|null $updated_at
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\DisbursementVoucherItem> $disbursement_voucher_items
 * @property-read int|null $disbursement_voucher_items_count
 * @method static \Illuminate\Database\Eloquent\Builder|DisbursementVoucher newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|DisbursementVoucher newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|DisbursementVoucher query()
 * @method static \Illuminate\Database\Eloquent\Builder|DisbursementVoucher whereAddress($value)
 * @method static \Illuminate\Database\Eloquent\Builder|DisbursementVoucher whereBookkeeperId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|DisbursementVoucher whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|DisbursementVoucher whereDescription($value)
 * @method static \Illuminate\Database\Eloquent\Builder|DisbursementVoucher whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|DisbursementVoucher whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|DisbursementVoucher whereReferenceNumber($value)
 * @method static \Illuminate\Database\Eloquent\Builder|DisbursementVoucher whereTransactionDate($value)
 * @method static \Illuminate\Database\Eloquent\Builder|DisbursementVoucher whereUpdatedAt($value)
 * @mixin \Eloquent
 */
	class IdeHelperDisbursementVoucher {}
}

namespace App\Models{
/**
 * App\Models\DisbursementVoucherItem
 *
 * @property int $id
 * @property int $disbursement_voucher_id
 * @property int $account_id
 * @property string|null $credit
 * @property string|null $debit
 * @property \Carbon\CarbonImmutable|null $created_at
 * @property \Carbon\CarbonImmutable|null $updated_at
 * @property-read \App\Models\Account $account
 * @property-read \App\Models\DisbursementVoucher $disbursement_voucher
 * @method static \Illuminate\Database\Eloquent\Builder|DisbursementVoucherItem newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|DisbursementVoucherItem newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|DisbursementVoucherItem query()
 * @method static \Illuminate\Database\Eloquent\Builder|DisbursementVoucherItem whereAccountId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|DisbursementVoucherItem whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|DisbursementVoucherItem whereCredit($value)
 * @method static \Illuminate\Database\Eloquent\Builder|DisbursementVoucherItem whereDebit($value)
 * @method static \Illuminate\Database\Eloquent\Builder|DisbursementVoucherItem whereDisbursementVoucherId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|DisbursementVoucherItem whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|DisbursementVoucherItem whereUpdatedAt($value)
 * @mixin \Eloquent
 */
	class IdeHelperDisbursementVoucherItem {}
}

namespace App\Models{
/**
 * App\Models\Division
 *
 * @property int $id
 * @property string $name
 * @property \Carbon\CarbonImmutable|null $created_at
 * @property \Carbon\CarbonImmutable|null $updated_at
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Member> $members
 * @property-read int|null $members_count
 * @method static \Illuminate\Database\Eloquent\Builder|Division newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Division newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Division query()
 * @method static \Illuminate\Database\Eloquent\Builder|Division whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Division whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Division whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Division whereUpdatedAt($value)
 * @mixin \Eloquent
 */
	class IdeHelperDivision {}
}

namespace App\Models{
/**
 * App\Models\Gender
 *
 * @property int $id
 * @property string $name
 * @property \Carbon\CarbonImmutable|null $created_at
 * @property \Carbon\CarbonImmutable|null $updated_at
 * @method static \Illuminate\Database\Eloquent\Builder|Gender newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Gender newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Gender query()
 * @method static \Illuminate\Database\Eloquent\Builder|Gender whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Gender whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Gender whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Gender whereUpdatedAt($value)
 * @mixin \Eloquent
 */
	class IdeHelperGender {}
}

namespace App\Models{
/**
 * App\Models\Imprest
 *
 * @property int $id
 * @property int $member_id
 * @property int $payment_type_id
 * @property string $reference_number
 * @property string $amount
 * @property string|null $deposit
 * @property string|null $withdrawal
 * @property string $interest_rate
 * @property string $interest
 * @property \Carbon\CarbonImmutable $transaction_date
 * @property \Carbon\CarbonImmutable|null $interest_date
 * @property string $balance
 * @property bool $accrued
 * @property int|null $cashier_id
 * @property \Carbon\CarbonImmutable|null $created_at
 * @property \Carbon\CarbonImmutable|null $updated_at
 * @property-read \App\Models\User|null $cashier
 * @property-read \App\Models\Member $member
 * @method static \Illuminate\Database\Eloquent\Builder|Imprest newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Imprest newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Imprest query()
 * @method static \Illuminate\Database\Eloquent\Builder|Imprest whereAccrued($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Imprest whereAmount($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Imprest whereBalance($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Imprest whereCashierId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Imprest whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Imprest whereDeposit($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Imprest whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Imprest whereInterest($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Imprest whereInterestDate($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Imprest whereInterestRate($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Imprest whereMemberId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Imprest wherePaymentTypeId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Imprest whereReferenceNumber($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Imprest whereTransactionDate($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Imprest whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Imprest whereWithdrawal($value)
 * @mixin \Eloquent
 */
	class IdeHelperImprest {}
}

namespace App\Models{
/**
 * App\Models\ImprestAccount
 *
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
 * @property-read \App\Models\AccountType $account_type
 * @property-read \Staudenmeir\LaravelAdjacencyList\Eloquent\Collection|\App\Models\ImprestAccount[] $children
 * @property-read int|null $children_count
 * @property-read \App\Models\Member|null $member
 * @property-read \App\Models\ImprestAccount|null $parent
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Transaction> $transactions
 * @property-read int|null $transactions_count
 * @property-read \Staudenmeir\LaravelAdjacencyList\Eloquent\Collection|\App\Models\ImprestAccount[] $ancestors The model's recursive parents.
 * @property-read int|null $ancestors_count
 * @property-read \Staudenmeir\LaravelAdjacencyList\Eloquent\Collection|\App\Models\ImprestAccount[] $ancestorsAndSelf The model's recursive parents and itself.
 * @property-read int|null $ancestors_and_self_count
 * @property-read \Staudenmeir\LaravelAdjacencyList\Eloquent\Collection|\App\Models\ImprestAccount[] $bloodline The model's ancestors, descendants and itself.
 * @property-read int|null $bloodline_count
 * @property-read \Staudenmeir\LaravelAdjacencyList\Eloquent\Collection|\App\Models\ImprestAccount[] $childrenAndSelf The model's direct children and itself.
 * @property-read int|null $children_and_self_count
 * @property-read \Staudenmeir\LaravelAdjacencyList\Eloquent\Collection|\App\Models\ImprestAccount[] $descendants The model's recursive children.
 * @property-read int|null $descendants_count
 * @property-read \Staudenmeir\LaravelAdjacencyList\Eloquent\Collection|\App\Models\ImprestAccount[] $descendantsAndSelf The model's recursive children and itself.
 * @property-read int|null $descendants_and_self_count
 * @property-read \Staudenmeir\LaravelAdjacencyList\Eloquent\Collection|\App\Models\ImprestAccount[] $parentAndSelf The model's direct parent and itself.
 * @property-read int|null $parent_and_self_count
 * @property-read \App\Models\ImprestAccount|null $rootAncestor The model's topmost parent.
 * @property-read \Staudenmeir\LaravelAdjacencyList\Eloquent\Collection|\App\Models\ImprestAccount[] $siblings The parent's other children.
 * @property-read int|null $siblings_count
 * @property-read \Staudenmeir\LaravelAdjacencyList\Eloquent\Collection|\App\Models\ImprestAccount[] $siblingsAndSelf All the parent's children.
 * @property-read int|null $siblings_and_self_count
 * @method static \Staudenmeir\LaravelAdjacencyList\Eloquent\Collection<int, static> all($columns = ['*'])
 * @method static \Staudenmeir\LaravelAdjacencyList\Eloquent\Builder|Account breadthFirst()
 * @method static \Staudenmeir\LaravelAdjacencyList\Eloquent\Builder|Account depthFirst()
 * @method static \Staudenmeir\LaravelAdjacencyList\Eloquent\Builder|Account doesntHaveChildren()
 * @method static \Staudenmeir\LaravelAdjacencyList\Eloquent\Collection<int, static> get($columns = ['*'])
 * @method static \Staudenmeir\LaravelAdjacencyList\Eloquent\Builder|ImprestAccount getExpressionGrammar()
 * @method static \Staudenmeir\LaravelAdjacencyList\Eloquent\Builder|Account hasChildren()
 * @method static \Staudenmeir\LaravelAdjacencyList\Eloquent\Builder|Account hasParent()
 * @method static \Staudenmeir\LaravelAdjacencyList\Eloquent\Builder|Account isLeaf()
 * @method static \Staudenmeir\LaravelAdjacencyList\Eloquent\Builder|Account isRoot()
 * @method static \Staudenmeir\LaravelAdjacencyList\Eloquent\Builder|ImprestAccount newModelQuery()
 * @method static \Staudenmeir\LaravelAdjacencyList\Eloquent\Builder|ImprestAccount newQuery()
 * @method static \Staudenmeir\LaravelAdjacencyList\Eloquent\Builder|ImprestAccount query()
 * @method static \Staudenmeir\LaravelAdjacencyList\Eloquent\Builder|Account tree($maxDepth = null)
 * @method static \Staudenmeir\LaravelAdjacencyList\Eloquent\Builder|Account treeOf(\Illuminate\Database\Eloquent\Model|callable $constraint, $maxDepth = null)
 * @method static \Staudenmeir\LaravelAdjacencyList\Eloquent\Builder|ImprestAccount whereAccountTypeId($value)
 * @method static \Staudenmeir\LaravelAdjacencyList\Eloquent\Builder|ImprestAccount whereAccountableId($value)
 * @method static \Staudenmeir\LaravelAdjacencyList\Eloquent\Builder|ImprestAccount whereAccountableType($value)
 * @method static \Staudenmeir\LaravelAdjacencyList\Eloquent\Builder|ImprestAccount whereCreatedAt($value)
 * @method static \Staudenmeir\LaravelAdjacencyList\Eloquent\Builder|Account whereDepth($operator, $value = null)
 * @method static \Staudenmeir\LaravelAdjacencyList\Eloquent\Builder|ImprestAccount whereId($value)
 * @method static \Staudenmeir\LaravelAdjacencyList\Eloquent\Builder|ImprestAccount whereMemberId($value)
 * @method static \Staudenmeir\LaravelAdjacencyList\Eloquent\Builder|ImprestAccount whereName($value)
 * @method static \Staudenmeir\LaravelAdjacencyList\Eloquent\Builder|ImprestAccount whereNumber($value)
 * @method static \Staudenmeir\LaravelAdjacencyList\Eloquent\Builder|ImprestAccount whereParentId($value)
 * @method static \Staudenmeir\LaravelAdjacencyList\Eloquent\Builder|ImprestAccount whereTag($value)
 * @method static \Staudenmeir\LaravelAdjacencyList\Eloquent\Builder|ImprestAccount whereUpdatedAt($value)
 * @method static \Staudenmeir\LaravelAdjacencyList\Eloquent\Builder|Account withCode()
 * @method static \Staudenmeir\LaravelAdjacencyList\Eloquent\Builder|ImprestAccount withGlobalScopes(array $scopes)
 * @method static \Staudenmeir\LaravelAdjacencyList\Eloquent\Builder|Account withRelationshipExpression($direction, callable $constraint, $initialDepth, $from = null, $maxDepth = null)
 * @mixin \Eloquent
 */
	class IdeHelperImprestAccount {}
}

namespace App\Models{
/**
 * App\Models\JournalEntryVoucher
 *
 * @property int $id
 * @property string $name
 * @property string|null $address
 * @property string $reference_number
 * @property string $description
 * @property \Carbon\CarbonImmutable $transaction_date
 * @property int $bookkeeper_id
 * @property \Carbon\CarbonImmutable|null $created_at
 * @property \Carbon\CarbonImmutable|null $updated_at
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\JournalEntryVoucherItem> $journal_entry_voucher_items
 * @property-read int|null $journal_entry_voucher_items_count
 * @method static \Illuminate\Database\Eloquent\Builder|JournalEntryVoucher newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|JournalEntryVoucher newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|JournalEntryVoucher query()
 * @method static \Illuminate\Database\Eloquent\Builder|JournalEntryVoucher whereAddress($value)
 * @method static \Illuminate\Database\Eloquent\Builder|JournalEntryVoucher whereBookkeeperId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|JournalEntryVoucher whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|JournalEntryVoucher whereDescription($value)
 * @method static \Illuminate\Database\Eloquent\Builder|JournalEntryVoucher whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|JournalEntryVoucher whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|JournalEntryVoucher whereReferenceNumber($value)
 * @method static \Illuminate\Database\Eloquent\Builder|JournalEntryVoucher whereTransactionDate($value)
 * @method static \Illuminate\Database\Eloquent\Builder|JournalEntryVoucher whereUpdatedAt($value)
 * @mixin \Eloquent
 */
	class IdeHelperJournalEntryVoucher {}
}

namespace App\Models{
/**
 * App\Models\JournalEntryVoucherItem
 *
 * @property int $id
 * @property int $journal_entry_voucher_id
 * @property int $account_id
 * @property string|null $credit
 * @property string|null $debit
 * @property \Carbon\CarbonImmutable|null $created_at
 * @property \Carbon\CarbonImmutable|null $updated_at
 * @property-read \App\Models\Account $account
 * @property-read \App\Models\JournalEntryVoucher $journal_entry_voucher
 * @method static \Illuminate\Database\Eloquent\Builder|JournalEntryVoucherItem newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|JournalEntryVoucherItem newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|JournalEntryVoucherItem query()
 * @method static \Illuminate\Database\Eloquent\Builder|JournalEntryVoucherItem whereAccountId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|JournalEntryVoucherItem whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|JournalEntryVoucherItem whereCredit($value)
 * @method static \Illuminate\Database\Eloquent\Builder|JournalEntryVoucherItem whereDebit($value)
 * @method static \Illuminate\Database\Eloquent\Builder|JournalEntryVoucherItem whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|JournalEntryVoucherItem whereJournalEntryVoucherId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|JournalEntryVoucherItem whereUpdatedAt($value)
 * @mixin \Eloquent
 */
	class IdeHelperJournalEntryVoucherItem {}
}

namespace App\Models{
/**
 * App\Models\Loan
 *
 * @property int $id
 * @property int $loan_account_id
 * @property int|null $loan_buyout_id
 * @property int $member_id
 * @property int $loan_application_id
 * @property int $loan_type_id
 * @property string $reference_number
 * @property string|null $check_number
 * @property string $priority_number
 * @property string $gross_amount
 * @property string|null $net_amount
 * @property array $deductions
 * @property int $number_of_terms
 * @property string $interest_rate
 * @property string $interest
 * @property string $service_fee
 * @property string $cbu_amount
 * @property string $imprest_amount
 * @property string $insurance_amount
 * @property string $loan_buyout_interest
 * @property string $loan_buyout_principal
 * @property string $deductions_amount
 * @property string $monthly_payment
 * @property string $outstanding_balance
 * @property \Carbon\CarbonImmutable $release_date
 * @property \Carbon\CarbonImmutable $transaction_date
 * @property bool $posted
 * @property \Carbon\CarbonImmutable|null $created_at
 * @property \Carbon\CarbonImmutable|null $updated_at
 * @property-read \App\Models\LoanAmortization|null $active_loan_amortization
 * @property-read mixed $deductions_list
 * @property-read mixed $maturity_date
 * @property-read \App\Models\LoanPayment|null $last_payment
 * @property-read \App\Models\LoanAccount $loan_account
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\LoanAmortization> $loan_amortizations
 * @property-read int|null $loan_amortizations_count
 * @property-read \App\Models\LoanApplication $loan_application
 * @property-read \App\Models\LoanType $loan_type
 * @property-read \App\Models\Member $member
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\LoanAmortization> $paid_loan_amortizations
 * @property-read int|null $paid_loan_amortizations_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\LoanPayment> $payments
 * @property-read int|null $payments_count
 * @method static \Illuminate\Database\Eloquent\Builder|Loan newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Loan newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Loan payable()
 * @method static \Illuminate\Database\Eloquent\Builder|Loan pending()
 * @method static \Illuminate\Database\Eloquent\Builder|Loan posted()
 * @method static \Illuminate\Database\Eloquent\Builder|Loan query()
 * @method static \Illuminate\Database\Eloquent\Builder|Loan whereCbuAmount($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Loan whereCheckNumber($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Loan whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Loan whereDeductions($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Loan whereDeductionsAmount($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Loan whereGrossAmount($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Loan whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Loan whereImprestAmount($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Loan whereInsuranceAmount($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Loan whereInterest($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Loan whereInterestRate($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Loan whereLoanAccountId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Loan whereLoanApplicationId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Loan whereLoanBuyoutId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Loan whereLoanBuyoutInterest($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Loan whereLoanBuyoutPrincipal($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Loan whereLoanTypeId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Loan whereMemberId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Loan whereMonthlyPayment($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Loan whereNetAmount($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Loan whereNumberOfTerms($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Loan whereOutstandingBalance($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Loan wherePosted($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Loan wherePriorityNumber($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Loan whereReferenceNumber($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Loan whereReleaseDate($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Loan whereServiceFee($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Loan whereTransactionDate($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Loan whereUpdatedAt($value)
 * @mixin \Eloquent
 */
	class IdeHelperLoan {}
}

namespace App\Models{
/**
 * App\Models\LoanAccount
 *
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
 * @property-read \App\Models\AccountType $account_type
 * @property-read \Staudenmeir\LaravelAdjacencyList\Eloquent\Collection|\App\Models\LoanAccount[] $children
 * @property-read int|null $children_count
 * @property-read \App\Models\LoanAccount|null $parent
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Transaction> $transactions
 * @property-read int|null $transactions_count
 * @property-read \Staudenmeir\LaravelAdjacencyList\Eloquent\Collection|\App\Models\LoanAccount[] $ancestors The model's recursive parents.
 * @property-read int|null $ancestors_count
 * @property-read \Staudenmeir\LaravelAdjacencyList\Eloquent\Collection|\App\Models\LoanAccount[] $ancestorsAndSelf The model's recursive parents and itself.
 * @property-read int|null $ancestors_and_self_count
 * @property-read \Staudenmeir\LaravelAdjacencyList\Eloquent\Collection|\App\Models\LoanAccount[] $bloodline The model's ancestors, descendants and itself.
 * @property-read int|null $bloodline_count
 * @property-read \Staudenmeir\LaravelAdjacencyList\Eloquent\Collection|\App\Models\LoanAccount[] $childrenAndSelf The model's direct children and itself.
 * @property-read int|null $children_and_self_count
 * @property-read \Staudenmeir\LaravelAdjacencyList\Eloquent\Collection|\App\Models\LoanAccount[] $descendants The model's recursive children.
 * @property-read int|null $descendants_count
 * @property-read \Staudenmeir\LaravelAdjacencyList\Eloquent\Collection|\App\Models\LoanAccount[] $descendantsAndSelf The model's recursive children and itself.
 * @property-read int|null $descendants_and_self_count
 * @property-read \Staudenmeir\LaravelAdjacencyList\Eloquent\Collection|\App\Models\LoanAccount[] $parentAndSelf The model's direct parent and itself.
 * @property-read int|null $parent_and_self_count
 * @property-read \App\Models\LoanAccount|null $rootAncestor The model's topmost parent.
 * @property-read \Staudenmeir\LaravelAdjacencyList\Eloquent\Collection|\App\Models\LoanAccount[] $siblings The parent's other children.
 * @property-read int|null $siblings_count
 * @property-read \Staudenmeir\LaravelAdjacencyList\Eloquent\Collection|\App\Models\LoanAccount[] $siblingsAndSelf All the parent's children.
 * @property-read int|null $siblings_and_self_count
 * @method static \Staudenmeir\LaravelAdjacencyList\Eloquent\Collection<int, static> all($columns = ['*'])
 * @method static \Staudenmeir\LaravelAdjacencyList\Eloquent\Builder|Account breadthFirst()
 * @method static \Staudenmeir\LaravelAdjacencyList\Eloquent\Builder|Account depthFirst()
 * @method static \Staudenmeir\LaravelAdjacencyList\Eloquent\Builder|Account doesntHaveChildren()
 * @method static \Staudenmeir\LaravelAdjacencyList\Eloquent\Collection<int, static> get($columns = ['*'])
 * @method static \Staudenmeir\LaravelAdjacencyList\Eloquent\Builder|LoanAccount getExpressionGrammar()
 * @method static \Staudenmeir\LaravelAdjacencyList\Eloquent\Builder|Account hasChildren()
 * @method static \Staudenmeir\LaravelAdjacencyList\Eloquent\Builder|Account hasParent()
 * @method static \Staudenmeir\LaravelAdjacencyList\Eloquent\Builder|Account isLeaf()
 * @method static \Staudenmeir\LaravelAdjacencyList\Eloquent\Builder|Account isRoot()
 * @method static \Staudenmeir\LaravelAdjacencyList\Eloquent\Builder|LoanAccount newModelQuery()
 * @method static \Staudenmeir\LaravelAdjacencyList\Eloquent\Builder|LoanAccount newQuery()
 * @method static \Staudenmeir\LaravelAdjacencyList\Eloquent\Builder|LoanAccount query()
 * @method static \Staudenmeir\LaravelAdjacencyList\Eloquent\Builder|Account tree($maxDepth = null)
 * @method static \Staudenmeir\LaravelAdjacencyList\Eloquent\Builder|Account treeOf(\Illuminate\Database\Eloquent\Model|callable $constraint, $maxDepth = null)
 * @method static \Staudenmeir\LaravelAdjacencyList\Eloquent\Builder|LoanAccount whereAccountTypeId($value)
 * @method static \Staudenmeir\LaravelAdjacencyList\Eloquent\Builder|LoanAccount whereAccountableId($value)
 * @method static \Staudenmeir\LaravelAdjacencyList\Eloquent\Builder|LoanAccount whereAccountableType($value)
 * @method static \Staudenmeir\LaravelAdjacencyList\Eloquent\Builder|LoanAccount whereCreatedAt($value)
 * @method static \Staudenmeir\LaravelAdjacencyList\Eloquent\Builder|Account whereDepth($operator, $value = null)
 * @method static \Staudenmeir\LaravelAdjacencyList\Eloquent\Builder|LoanAccount whereId($value)
 * @method static \Staudenmeir\LaravelAdjacencyList\Eloquent\Builder|LoanAccount whereMemberId($value)
 * @method static \Staudenmeir\LaravelAdjacencyList\Eloquent\Builder|LoanAccount whereName($value)
 * @method static \Staudenmeir\LaravelAdjacencyList\Eloquent\Builder|LoanAccount whereNumber($value)
 * @method static \Staudenmeir\LaravelAdjacencyList\Eloquent\Builder|LoanAccount whereParentId($value)
 * @method static \Staudenmeir\LaravelAdjacencyList\Eloquent\Builder|LoanAccount whereTag($value)
 * @method static \Staudenmeir\LaravelAdjacencyList\Eloquent\Builder|LoanAccount whereUpdatedAt($value)
 * @method static \Staudenmeir\LaravelAdjacencyList\Eloquent\Builder|Account withCode()
 * @method static \Staudenmeir\LaravelAdjacencyList\Eloquent\Builder|LoanAccount withGlobalScopes(array $scopes)
 * @method static \Staudenmeir\LaravelAdjacencyList\Eloquent\Builder|Account withRelationshipExpression($direction, callable $constraint, $initialDepth, $from = null, $maxDepth = null)
 * @mixin \Eloquent
 */
	class IdeHelperLoanAccount {}
}

namespace App\Models{
/**
 * App\Models\LoanAmortization
 *
 * @property int $id
 * @property int $loan_id
 * @property \Carbon\CarbonImmutable $date
 * @property string|null $billable_date
 * @property int $term
 * @property int $days
 * @property string $amortization
 * @property string $interest
 * @property string $principal
 * @property string $previous_balance
 * @property string|null $outstanding_balance
 * @property \Carbon\CarbonImmutable|null $created_at
 * @property \Carbon\CarbonImmutable|null $updated_at
 * @property-read \App\Models\Loan $loan
 * @method static \Illuminate\Database\Eloquent\Builder|LoanAmortization newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|LoanAmortization newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|LoanAmortization query()
 * @method static \Illuminate\Database\Eloquent\Builder|LoanAmortization whereAmortization($value)
 * @method static \Illuminate\Database\Eloquent\Builder|LoanAmortization whereBillableDate($value)
 * @method static \Illuminate\Database\Eloquent\Builder|LoanAmortization whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|LoanAmortization whereDate($value)
 * @method static \Illuminate\Database\Eloquent\Builder|LoanAmortization whereDays($value)
 * @method static \Illuminate\Database\Eloquent\Builder|LoanAmortization whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|LoanAmortization whereInterest($value)
 * @method static \Illuminate\Database\Eloquent\Builder|LoanAmortization whereLoanId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|LoanAmortization whereOutstandingBalance($value)
 * @method static \Illuminate\Database\Eloquent\Builder|LoanAmortization wherePreviousBalance($value)
 * @method static \Illuminate\Database\Eloquent\Builder|LoanAmortization wherePrincipal($value)
 * @method static \Illuminate\Database\Eloquent\Builder|LoanAmortization whereTerm($value)
 * @method static \Illuminate\Database\Eloquent\Builder|LoanAmortization whereUpdatedAt($value)
 * @mixin \Eloquent
 */
	class IdeHelperLoanAmortization {}
}

namespace App\Models{
/**
 * App\Models\LoanApplication
 *
 * @property int $id
 * @property int $member_id
 * @property int|null $processor_id
 * @property int $loan_type_id
 * @property string $desired_amount
 * @property int $number_of_terms
 * @property string|null $reference_number
 * @property string|null $priority_number
 * @property string|null $purpose
 * @property string $monthly_payment
 * @property int $status
 * @property \Carbon\CarbonImmutable $transaction_date
 * @property int|null $disapproval_reason_id
 * @property \Carbon\CarbonImmutable|null $disapproval_date
 * @property \Spatie\LaravelData\DataCollection|null $approvals
 * @property string|null $remarks
 * @property \Carbon\CarbonImmutable|null $created_at
 * @property \Carbon\CarbonImmutable|null $updated_at
 * @property-read \App\Models\DisapprovalReason|null $disapproval_reason
 * @property-read mixed $status_name
 * @property-read \App\Models\Loan|null $loan
 * @property-read \App\Models\LoanType $loan_type
 * @property-read \App\Models\Member $member
 * @property-read \App\Models\User|null $processor
 * @method static \Illuminate\Database\Eloquent\Builder|LoanApplication newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|LoanApplication newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|LoanApplication query()
 * @method static \Illuminate\Database\Eloquent\Builder|LoanApplication whereApprovals($value)
 * @method static \Illuminate\Database\Eloquent\Builder|LoanApplication whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|LoanApplication whereDesiredAmount($value)
 * @method static \Illuminate\Database\Eloquent\Builder|LoanApplication whereDisapprovalDate($value)
 * @method static \Illuminate\Database\Eloquent\Builder|LoanApplication whereDisapprovalReasonId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|LoanApplication whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|LoanApplication whereLoanTypeId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|LoanApplication whereMemberId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|LoanApplication whereMonthlyPayment($value)
 * @method static \Illuminate\Database\Eloquent\Builder|LoanApplication whereNumberOfTerms($value)
 * @method static \Illuminate\Database\Eloquent\Builder|LoanApplication wherePriorityNumber($value)
 * @method static \Illuminate\Database\Eloquent\Builder|LoanApplication whereProcessorId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|LoanApplication wherePurpose($value)
 * @method static \Illuminate\Database\Eloquent\Builder|LoanApplication whereReferenceNumber($value)
 * @method static \Illuminate\Database\Eloquent\Builder|LoanApplication whereRemarks($value)
 * @method static \Illuminate\Database\Eloquent\Builder|LoanApplication whereStatus($value)
 * @method static \Illuminate\Database\Eloquent\Builder|LoanApplication whereTransactionDate($value)
 * @method static \Illuminate\Database\Eloquent\Builder|LoanApplication whereUpdatedAt($value)
 * @mixin \Eloquent
 */
	class IdeHelperLoanApplication {}
}

namespace App\Models{
/**
 * App\Models\LoanBilling
 *
 * @property int $id
 * @property \Carbon\CarbonImmutable $date
 * @property string|null $billable_date
 * @property int|null $payment_type_id
 * @property string|null $reference_number
 * @property int $loan_type_id
 * @property int|null $cashier_id
 * @property bool $posted
 * @property \Carbon\CarbonImmutable|null $created_at
 * @property \Carbon\CarbonImmutable|null $updated_at
 * @property-read \App\Models\User|null $cashier
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\LoanBillingPayment> $loan_billing_payments
 * @property-read int|null $loan_billing_payments_count
 * @property-read \App\Models\LoanType $loan_type
 * @method static \Illuminate\Database\Eloquent\Builder|LoanBilling newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|LoanBilling newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|LoanBilling query()
 * @method static \Illuminate\Database\Eloquent\Builder|LoanBilling whereBillableDate($value)
 * @method static \Illuminate\Database\Eloquent\Builder|LoanBilling whereCashierId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|LoanBilling whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|LoanBilling whereDate($value)
 * @method static \Illuminate\Database\Eloquent\Builder|LoanBilling whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|LoanBilling whereLoanTypeId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|LoanBilling wherePaymentTypeId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|LoanBilling wherePosted($value)
 * @method static \Illuminate\Database\Eloquent\Builder|LoanBilling whereReferenceNumber($value)
 * @method static \Illuminate\Database\Eloquent\Builder|LoanBilling whereUpdatedAt($value)
 * @mixin \Eloquent
 */
	class IdeHelperLoanBilling {}
}

namespace App\Models{
/**
 * App\Models\LoanBillingPayment
 *
 * @property int $id
 * @property int $loan_billing_id
 * @property int $member_id
 * @property int $loan_id
 * @property string $amount_due
 * @property string $amount_paid
 * @property bool $posted
 * @property \Carbon\CarbonImmutable|null $created_at
 * @property \Carbon\CarbonImmutable|null $updated_at
 * @property-read \App\Models\Loan $loan
 * @property-read \App\Models\LoanBilling $loan_billing
 * @property-read \App\Models\Member $member
 * @method static \Illuminate\Database\Eloquent\Builder|LoanBillingPayment newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|LoanBillingPayment newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|LoanBillingPayment query()
 * @method static \Illuminate\Database\Eloquent\Builder|LoanBillingPayment whereAmountDue($value)
 * @method static \Illuminate\Database\Eloquent\Builder|LoanBillingPayment whereAmountPaid($value)
 * @method static \Illuminate\Database\Eloquent\Builder|LoanBillingPayment whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|LoanBillingPayment whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|LoanBillingPayment whereLoanBillingId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|LoanBillingPayment whereLoanId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|LoanBillingPayment whereMemberId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|LoanBillingPayment wherePosted($value)
 * @method static \Illuminate\Database\Eloquent\Builder|LoanBillingPayment whereUpdatedAt($value)
 * @mixin \Eloquent
 */
	class IdeHelperLoanBillingPayment {}
}

namespace App\Models{
/**
 * App\Models\LoanPayment
 *
 * @property int $id
 * @property bool $buy_out
 * @property int $loan_id
 * @property string $amount
 * @property string $interest_payment
 * @property string $principal_payment
 * @property string $surcharge_payment
 * @property int $payment_type_id
 * @property string $reference_number
 * @property string|null $remarks
 * @property \Carbon\CarbonImmutable $transaction_date
 * @property int|null $cashier_id
 * @property \Carbon\CarbonImmutable|null $created_at
 * @property \Carbon\CarbonImmutable|null $updated_at
 * @property-read \App\Models\User|null $cashier
 * @property-read \App\Models\Loan $loan
 * @method static \Illuminate\Database\Eloquent\Builder|LoanPayment newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|LoanPayment newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|LoanPayment query()
 * @method static \Illuminate\Database\Eloquent\Builder|LoanPayment whereAmount($value)
 * @method static \Illuminate\Database\Eloquent\Builder|LoanPayment whereBuyOut($value)
 * @method static \Illuminate\Database\Eloquent\Builder|LoanPayment whereCashierId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|LoanPayment whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|LoanPayment whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|LoanPayment whereInterestPayment($value)
 * @method static \Illuminate\Database\Eloquent\Builder|LoanPayment whereLoanId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|LoanPayment wherePaymentTypeId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|LoanPayment wherePrincipalPayment($value)
 * @method static \Illuminate\Database\Eloquent\Builder|LoanPayment whereReferenceNumber($value)
 * @method static \Illuminate\Database\Eloquent\Builder|LoanPayment whereRemarks($value)
 * @method static \Illuminate\Database\Eloquent\Builder|LoanPayment whereSurchargePayment($value)
 * @method static \Illuminate\Database\Eloquent\Builder|LoanPayment whereTransactionDate($value)
 * @method static \Illuminate\Database\Eloquent\Builder|LoanPayment whereUpdatedAt($value)
 * @mixin \Eloquent
 */
	class IdeHelperLoanPayment {}
}

namespace App\Models{
/**
 * App\Models\LoanType
 *
 * @property int $id
 * @property string $code
 * @property string $name
 * @property string $minimum_cbu
 * @property string $max_amount
 * @property string $interest_rate
 * @property string $surcharge_rate
 * @property string $service_fee
 * @property string $cbu_common
 * @property string $imprest
 * @property string $insurance
 * @property \Carbon\CarbonImmutable|null $created_at
 * @property \Carbon\CarbonImmutable|null $updated_at
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Loan> $loans
 * @property-read int|null $loans_count
 * @method static \Illuminate\Database\Eloquent\Builder|LoanType newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|LoanType newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|LoanType query()
 * @method static \Illuminate\Database\Eloquent\Builder|LoanType whereCbuCommon($value)
 * @method static \Illuminate\Database\Eloquent\Builder|LoanType whereCode($value)
 * @method static \Illuminate\Database\Eloquent\Builder|LoanType whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|LoanType whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|LoanType whereImprest($value)
 * @method static \Illuminate\Database\Eloquent\Builder|LoanType whereInsurance($value)
 * @method static \Illuminate\Database\Eloquent\Builder|LoanType whereInterestRate($value)
 * @method static \Illuminate\Database\Eloquent\Builder|LoanType whereMaxAmount($value)
 * @method static \Illuminate\Database\Eloquent\Builder|LoanType whereMinimumCbu($value)
 * @method static \Illuminate\Database\Eloquent\Builder|LoanType whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|LoanType whereServiceFee($value)
 * @method static \Illuminate\Database\Eloquent\Builder|LoanType whereSurchargeRate($value)
 * @method static \Illuminate\Database\Eloquent\Builder|LoanType whereUpdatedAt($value)
 * @mixin \Eloquent
 */
	class IdeHelperLoanType {}
}

namespace App\Models{
/**
 * App\Models\LoveGift
 *
 * @property int $id
 * @property int $member_id
 * @property int $payment_type_id
 * @property string $reference_number
 * @property string $amount
 * @property string|null $deposit
 * @property string|null $withdrawal
 * @property string $interest_rate
 * @property string $interest
 * @property \Carbon\CarbonImmutable $transaction_date
 * @property \Carbon\CarbonImmutable|null $interest_date
 * @property string $balance
 * @property bool $accrued
 * @property int|null $cashier_id
 * @property \Carbon\CarbonImmutable|null $created_at
 * @property \Carbon\CarbonImmutable|null $updated_at
 * @property-read \App\Models\User|null $cashier
 * @property-read \App\Models\Member $member
 * @method static \Illuminate\Database\Eloquent\Builder|LoveGift newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|LoveGift newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|LoveGift query()
 * @method static \Illuminate\Database\Eloquent\Builder|LoveGift whereAccrued($value)
 * @method static \Illuminate\Database\Eloquent\Builder|LoveGift whereAmount($value)
 * @method static \Illuminate\Database\Eloquent\Builder|LoveGift whereBalance($value)
 * @method static \Illuminate\Database\Eloquent\Builder|LoveGift whereCashierId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|LoveGift whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|LoveGift whereDeposit($value)
 * @method static \Illuminate\Database\Eloquent\Builder|LoveGift whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|LoveGift whereInterest($value)
 * @method static \Illuminate\Database\Eloquent\Builder|LoveGift whereInterestDate($value)
 * @method static \Illuminate\Database\Eloquent\Builder|LoveGift whereInterestRate($value)
 * @method static \Illuminate\Database\Eloquent\Builder|LoveGift whereMemberId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|LoveGift wherePaymentTypeId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|LoveGift whereReferenceNumber($value)
 * @method static \Illuminate\Database\Eloquent\Builder|LoveGift whereTransactionDate($value)
 * @method static \Illuminate\Database\Eloquent\Builder|LoveGift whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|LoveGift whereWithdrawal($value)
 * @mixin \Eloquent
 */
	class IdeHelperLoveGift {}
}

namespace App\Models{
/**
 * App\Models\LoveGiftAccount
 *
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
 * @property-read \App\Models\AccountType $account_type
 * @property-read \Staudenmeir\LaravelAdjacencyList\Eloquent\Collection|\App\Models\LoveGiftAccount[] $children
 * @property-read int|null $children_count
 * @property-read \App\Models\Member|null $member
 * @property-read \App\Models\LoveGiftAccount|null $parent
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Transaction> $transactions
 * @property-read int|null $transactions_count
 * @property-read \Staudenmeir\LaravelAdjacencyList\Eloquent\Collection|\App\Models\LoveGiftAccount[] $ancestors The model's recursive parents.
 * @property-read int|null $ancestors_count
 * @property-read \Staudenmeir\LaravelAdjacencyList\Eloquent\Collection|\App\Models\LoveGiftAccount[] $ancestorsAndSelf The model's recursive parents and itself.
 * @property-read int|null $ancestors_and_self_count
 * @property-read \Staudenmeir\LaravelAdjacencyList\Eloquent\Collection|\App\Models\LoveGiftAccount[] $bloodline The model's ancestors, descendants and itself.
 * @property-read int|null $bloodline_count
 * @property-read \Staudenmeir\LaravelAdjacencyList\Eloquent\Collection|\App\Models\LoveGiftAccount[] $childrenAndSelf The model's direct children and itself.
 * @property-read int|null $children_and_self_count
 * @property-read \Staudenmeir\LaravelAdjacencyList\Eloquent\Collection|\App\Models\LoveGiftAccount[] $descendants The model's recursive children.
 * @property-read int|null $descendants_count
 * @property-read \Staudenmeir\LaravelAdjacencyList\Eloquent\Collection|\App\Models\LoveGiftAccount[] $descendantsAndSelf The model's recursive children and itself.
 * @property-read int|null $descendants_and_self_count
 * @property-read \Staudenmeir\LaravelAdjacencyList\Eloquent\Collection|\App\Models\LoveGiftAccount[] $parentAndSelf The model's direct parent and itself.
 * @property-read int|null $parent_and_self_count
 * @property-read \App\Models\LoveGiftAccount|null $rootAncestor The model's topmost parent.
 * @property-read \Staudenmeir\LaravelAdjacencyList\Eloquent\Collection|\App\Models\LoveGiftAccount[] $siblings The parent's other children.
 * @property-read int|null $siblings_count
 * @property-read \Staudenmeir\LaravelAdjacencyList\Eloquent\Collection|\App\Models\LoveGiftAccount[] $siblingsAndSelf All the parent's children.
 * @property-read int|null $siblings_and_self_count
 * @method static \Staudenmeir\LaravelAdjacencyList\Eloquent\Collection<int, static> all($columns = ['*'])
 * @method static \Staudenmeir\LaravelAdjacencyList\Eloquent\Builder|Account breadthFirst()
 * @method static \Staudenmeir\LaravelAdjacencyList\Eloquent\Builder|Account depthFirst()
 * @method static \Staudenmeir\LaravelAdjacencyList\Eloquent\Builder|Account doesntHaveChildren()
 * @method static \Staudenmeir\LaravelAdjacencyList\Eloquent\Collection<int, static> get($columns = ['*'])
 * @method static \Staudenmeir\LaravelAdjacencyList\Eloquent\Builder|LoveGiftAccount getExpressionGrammar()
 * @method static \Staudenmeir\LaravelAdjacencyList\Eloquent\Builder|Account hasChildren()
 * @method static \Staudenmeir\LaravelAdjacencyList\Eloquent\Builder|Account hasParent()
 * @method static \Staudenmeir\LaravelAdjacencyList\Eloquent\Builder|Account isLeaf()
 * @method static \Staudenmeir\LaravelAdjacencyList\Eloquent\Builder|Account isRoot()
 * @method static \Staudenmeir\LaravelAdjacencyList\Eloquent\Builder|LoveGiftAccount newModelQuery()
 * @method static \Staudenmeir\LaravelAdjacencyList\Eloquent\Builder|LoveGiftAccount newQuery()
 * @method static \Staudenmeir\LaravelAdjacencyList\Eloquent\Builder|LoveGiftAccount query()
 * @method static \Staudenmeir\LaravelAdjacencyList\Eloquent\Builder|Account tree($maxDepth = null)
 * @method static \Staudenmeir\LaravelAdjacencyList\Eloquent\Builder|Account treeOf(\Illuminate\Database\Eloquent\Model|callable $constraint, $maxDepth = null)
 * @method static \Staudenmeir\LaravelAdjacencyList\Eloquent\Builder|LoveGiftAccount whereAccountTypeId($value)
 * @method static \Staudenmeir\LaravelAdjacencyList\Eloquent\Builder|LoveGiftAccount whereAccountableId($value)
 * @method static \Staudenmeir\LaravelAdjacencyList\Eloquent\Builder|LoveGiftAccount whereAccountableType($value)
 * @method static \Staudenmeir\LaravelAdjacencyList\Eloquent\Builder|LoveGiftAccount whereCreatedAt($value)
 * @method static \Staudenmeir\LaravelAdjacencyList\Eloquent\Builder|Account whereDepth($operator, $value = null)
 * @method static \Staudenmeir\LaravelAdjacencyList\Eloquent\Builder|LoveGiftAccount whereId($value)
 * @method static \Staudenmeir\LaravelAdjacencyList\Eloquent\Builder|LoveGiftAccount whereMemberId($value)
 * @method static \Staudenmeir\LaravelAdjacencyList\Eloquent\Builder|LoveGiftAccount whereName($value)
 * @method static \Staudenmeir\LaravelAdjacencyList\Eloquent\Builder|LoveGiftAccount whereNumber($value)
 * @method static \Staudenmeir\LaravelAdjacencyList\Eloquent\Builder|LoveGiftAccount whereParentId($value)
 * @method static \Staudenmeir\LaravelAdjacencyList\Eloquent\Builder|LoveGiftAccount whereTag($value)
 * @method static \Staudenmeir\LaravelAdjacencyList\Eloquent\Builder|LoveGiftAccount whereUpdatedAt($value)
 * @method static \Staudenmeir\LaravelAdjacencyList\Eloquent\Builder|Account withCode()
 * @method static \Staudenmeir\LaravelAdjacencyList\Eloquent\Builder|LoveGiftAccount withGlobalScopes(array $scopes)
 * @method static \Staudenmeir\LaravelAdjacencyList\Eloquent\Builder|Account withRelationshipExpression($direction, callable $constraint, $initialDepth, $from = null, $maxDepth = null)
 * @mixin \Eloquent
 */
	class IdeHelperLoveGiftAccount {}
}

namespace App\Models{
/**
 * App\Models\Member
 *
 * @property int $id
 * @property string|null $mpc_code
 * @property int|null $member_type_id
 * @property int|null $division_id
 * @property int|null $user_id
 * @property string $first_name
 * @property string $last_name
 * @property string|null $middle_initial
 * @property string|null $full_name
 * @property string|null $alt_full_name
 * @property string|null $tin
 * @property int|null $gender_id
 * @property int|null $civil_status_id
 * @property int $patronage_status_id
 * @property \Carbon\CarbonImmutable|null $dob
 * @property string|null $place_of_birth
 * @property string|null $address
 * @property int|null $occupation_id
 * @property string|null $present_employer
 * @property string|null $highest_educational_attainment
 * @property \Spatie\LaravelData\DataCollection|null $dependents
 * @property int|null $dependents_count
 * @property int|null $religion_id
 * @property string|null $annual_income
 * @property array|null $other_income_sources
 * @property int|null $region_id
 * @property int|null $province_id
 * @property int|null $municipality_id
 * @property int|null $barangay_id
 * @property string|null $terminated_at
 * @property \Carbon\CarbonImmutable|null $created_at
 * @property \Carbon\CarbonImmutable|null $updated_at
 * @property-read \Staudenmeir\LaravelAdjacencyList\Eloquent\Collection<int, \App\Models\Account> $accounts
 * @property-read int|null $accounts_count
 * @property-read \App\Models\Barangay|null $barangay
 * @property-read \App\Models\Account|null $capital_subscription_account
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\CapitalSubscriptionPayment> $capital_subscription_payments
 * @property-read int|null $capital_subscription_payments_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\CapitalSubscription> $capital_subscriptions
 * @property-read int|null $capital_subscriptions_count
 * @property-read \App\Models\CapitalSubscription|null $capital_subscriptions_common
 * @property-read \App\Models\CivilStatus|null $civil_status
 * @property-read \App\Models\Division|null $division
 * @property-read \App\Models\Gender|null $gender
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
 * @property-read \App\Models\MemberType|null $member_type
 * @property-read \App\Models\MembershipStatus|null $membership_acceptance
 * @property-read \App\Models\MembershipStatus|null $membership_termination
 * @property-read \App\Models\Municipality|null $municipality
 * @property-read \App\Models\Occupation|null $occupation
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
 * @method static \Illuminate\Database\Eloquent\Builder|Member newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Member newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Member query()
 * @method static \Illuminate\Database\Eloquent\Builder|Member whereAddress($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Member whereAltFullName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Member whereAnnualIncome($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Member whereBarangayId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Member whereCivilStatusId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Member whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Member whereDependents($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Member whereDependentsCount($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Member whereDivisionId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Member whereDob($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Member whereFirstName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Member whereFullName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Member whereGenderId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Member whereHighestEducationalAttainment($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Member whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Member whereLastName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Member whereMemberTypeId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Member whereMiddleInitial($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Member whereMpcCode($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Member whereMunicipalityId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Member whereOccupationId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Member whereOtherIncomeSources($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Member wherePatronageStatusId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Member wherePlaceOfBirth($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Member wherePresentEmployer($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Member whereProvinceId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Member whereRegionId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Member whereReligionId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Member whereTerminatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Member whereTin($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Member whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Member whereUserId($value)
 * @mixin \Eloquent
 */
	class IdeHelperMember {}
}

namespace App\Models{
/**
 * App\Models\MemberAccount
 *
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
 * @property-read \App\Models\AccountType $account_type
 * @property-read \Staudenmeir\LaravelAdjacencyList\Eloquent\Collection|\App\Models\MemberAccount[] $children
 * @property-read int|null $children_count
 * @property-read \App\Models\MemberAccount|null $parent
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Transaction> $transactions
 * @property-read int|null $transactions_count
 * @property-read \Staudenmeir\LaravelAdjacencyList\Eloquent\Collection|\App\Models\MemberAccount[] $ancestors The model's recursive parents.
 * @property-read int|null $ancestors_count
 * @property-read \Staudenmeir\LaravelAdjacencyList\Eloquent\Collection|\App\Models\MemberAccount[] $ancestorsAndSelf The model's recursive parents and itself.
 * @property-read int|null $ancestors_and_self_count
 * @property-read \Staudenmeir\LaravelAdjacencyList\Eloquent\Collection|\App\Models\MemberAccount[] $bloodline The model's ancestors, descendants and itself.
 * @property-read int|null $bloodline_count
 * @property-read \Staudenmeir\LaravelAdjacencyList\Eloquent\Collection|\App\Models\MemberAccount[] $childrenAndSelf The model's direct children and itself.
 * @property-read int|null $children_and_self_count
 * @property-read \Staudenmeir\LaravelAdjacencyList\Eloquent\Collection|\App\Models\MemberAccount[] $descendants The model's recursive children.
 * @property-read int|null $descendants_count
 * @property-read \Staudenmeir\LaravelAdjacencyList\Eloquent\Collection|\App\Models\MemberAccount[] $descendantsAndSelf The model's recursive children and itself.
 * @property-read int|null $descendants_and_self_count
 * @property-read \Staudenmeir\LaravelAdjacencyList\Eloquent\Collection|\App\Models\MemberAccount[] $parentAndSelf The model's direct parent and itself.
 * @property-read int|null $parent_and_self_count
 * @property-read \App\Models\MemberAccount|null $rootAncestor The model's topmost parent.
 * @property-read \Staudenmeir\LaravelAdjacencyList\Eloquent\Collection|\App\Models\MemberAccount[] $siblings The parent's other children.
 * @property-read int|null $siblings_count
 * @property-read \Staudenmeir\LaravelAdjacencyList\Eloquent\Collection|\App\Models\MemberAccount[] $siblingsAndSelf All the parent's children.
 * @property-read int|null $siblings_and_self_count
 * @method static \Staudenmeir\LaravelAdjacencyList\Eloquent\Collection<int, static> all($columns = ['*'])
 * @method static \Staudenmeir\LaravelAdjacencyList\Eloquent\Builder|Account breadthFirst()
 * @method static \Staudenmeir\LaravelAdjacencyList\Eloquent\Builder|Account depthFirst()
 * @method static \Staudenmeir\LaravelAdjacencyList\Eloquent\Builder|Account doesntHaveChildren()
 * @method static \Staudenmeir\LaravelAdjacencyList\Eloquent\Collection<int, static> get($columns = ['*'])
 * @method static \Staudenmeir\LaravelAdjacencyList\Eloquent\Builder|MemberAccount getExpressionGrammar()
 * @method static \Staudenmeir\LaravelAdjacencyList\Eloquent\Builder|Account hasChildren()
 * @method static \Staudenmeir\LaravelAdjacencyList\Eloquent\Builder|Account hasParent()
 * @method static \Staudenmeir\LaravelAdjacencyList\Eloquent\Builder|Account isLeaf()
 * @method static \Staudenmeir\LaravelAdjacencyList\Eloquent\Builder|Account isRoot()
 * @method static \Staudenmeir\LaravelAdjacencyList\Eloquent\Builder|MemberAccount newModelQuery()
 * @method static \Staudenmeir\LaravelAdjacencyList\Eloquent\Builder|MemberAccount newQuery()
 * @method static \Staudenmeir\LaravelAdjacencyList\Eloquent\Builder|MemberAccount query()
 * @method static \Staudenmeir\LaravelAdjacencyList\Eloquent\Builder|Account tree($maxDepth = null)
 * @method static \Staudenmeir\LaravelAdjacencyList\Eloquent\Builder|Account treeOf(\Illuminate\Database\Eloquent\Model|callable $constraint, $maxDepth = null)
 * @method static \Staudenmeir\LaravelAdjacencyList\Eloquent\Builder|MemberAccount whereAccountTypeId($value)
 * @method static \Staudenmeir\LaravelAdjacencyList\Eloquent\Builder|MemberAccount whereAccountableId($value)
 * @method static \Staudenmeir\LaravelAdjacencyList\Eloquent\Builder|MemberAccount whereAccountableType($value)
 * @method static \Staudenmeir\LaravelAdjacencyList\Eloquent\Builder|MemberAccount whereCreatedAt($value)
 * @method static \Staudenmeir\LaravelAdjacencyList\Eloquent\Builder|Account whereDepth($operator, $value = null)
 * @method static \Staudenmeir\LaravelAdjacencyList\Eloquent\Builder|MemberAccount whereId($value)
 * @method static \Staudenmeir\LaravelAdjacencyList\Eloquent\Builder|MemberAccount whereMemberId($value)
 * @method static \Staudenmeir\LaravelAdjacencyList\Eloquent\Builder|MemberAccount whereName($value)
 * @method static \Staudenmeir\LaravelAdjacencyList\Eloquent\Builder|MemberAccount whereNumber($value)
 * @method static \Staudenmeir\LaravelAdjacencyList\Eloquent\Builder|MemberAccount whereParentId($value)
 * @method static \Staudenmeir\LaravelAdjacencyList\Eloquent\Builder|MemberAccount whereTag($value)
 * @method static \Staudenmeir\LaravelAdjacencyList\Eloquent\Builder|MemberAccount whereUpdatedAt($value)
 * @method static \Staudenmeir\LaravelAdjacencyList\Eloquent\Builder|Account withCode()
 * @method static \Staudenmeir\LaravelAdjacencyList\Eloquent\Builder|MemberAccount withGlobalScopes(array $scopes)
 * @method static \Staudenmeir\LaravelAdjacencyList\Eloquent\Builder|Account withRelationshipExpression($direction, callable $constraint, $initialDepth, $from = null, $maxDepth = null)
 * @mixin \Eloquent
 */
	class IdeHelperMemberAccount {}
}

namespace App\Models{
/**
 * App\Models\MemberType
 *
 * @property int $id
 * @property string $name
 * @property string $surcharge_rate
 * @property string $par_value
 * @property string $default_number_of_shares
 * @property string $default_amount_subscribed
 * @property string $minimum_initial_payment
 * @property int $initial_number_of_terms
 * @property int $additional_number_of_terms
 * @property \Carbon\CarbonImmutable|null $created_at
 * @property \Carbon\CarbonImmutable|null $updated_at
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Member> $members
 * @property-read int|null $members_count
 * @method static \Illuminate\Database\Eloquent\Builder|MemberType newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|MemberType newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|MemberType query()
 * @method static \Illuminate\Database\Eloquent\Builder|MemberType whereAdditionalNumberOfTerms($value)
 * @method static \Illuminate\Database\Eloquent\Builder|MemberType whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|MemberType whereDefaultAmountSubscribed($value)
 * @method static \Illuminate\Database\Eloquent\Builder|MemberType whereDefaultNumberOfShares($value)
 * @method static \Illuminate\Database\Eloquent\Builder|MemberType whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|MemberType whereInitialNumberOfTerms($value)
 * @method static \Illuminate\Database\Eloquent\Builder|MemberType whereMinimumInitialPayment($value)
 * @method static \Illuminate\Database\Eloquent\Builder|MemberType whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|MemberType whereParValue($value)
 * @method static \Illuminate\Database\Eloquent\Builder|MemberType whereSurchargeRate($value)
 * @method static \Illuminate\Database\Eloquent\Builder|MemberType whereUpdatedAt($value)
 * @mixin \Eloquent
 */
	class IdeHelperMemberType {}
}

namespace App\Models{
/**
 * App\Models\MembershipStatus
 *
 * @property int $id
 * @property int $member_id
 * @property int $type
 * @property string|null $bod_resolution
 * @property \Carbon\CarbonImmutable $effectivity_date
 * @property \Carbon\CarbonImmutable|null $created_at
 * @property \Carbon\CarbonImmutable|null $updated_at
 * @property-read \App\Models\Member $member
 * @method static \Illuminate\Database\Eloquent\Builder|MembershipStatus newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|MembershipStatus newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|MembershipStatus query()
 * @method static \Illuminate\Database\Eloquent\Builder|MembershipStatus whereBodResolution($value)
 * @method static \Illuminate\Database\Eloquent\Builder|MembershipStatus whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|MembershipStatus whereEffectivityDate($value)
 * @method static \Illuminate\Database\Eloquent\Builder|MembershipStatus whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|MembershipStatus whereMemberId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|MembershipStatus whereType($value)
 * @method static \Illuminate\Database\Eloquent\Builder|MembershipStatus whereUpdatedAt($value)
 * @mixin \Eloquent
 */
	class IdeHelperMembershipStatus {}
}

namespace App\Models{
/**
 * App\Models\Municipality
 *
 * @property int $id
 * @property int|null $province_id
 * @property string|null $name
 * @property-read \App\Models\Province|null $province
 * @method static \Illuminate\Database\Eloquent\Builder|Municipality newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Municipality newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Municipality query()
 * @method static \Illuminate\Database\Eloquent\Builder|Municipality whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Municipality whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Municipality whereProvinceId($value)
 * @mixin \Eloquent
 */
	class IdeHelperMunicipality {}
}

namespace App\Models{
/**
 * App\Models\Occupation
 *
 * @property int $id
 * @property string $name
 * @property \Carbon\CarbonImmutable|null $created_at
 * @property \Carbon\CarbonImmutable|null $updated_at
 * @method static \Illuminate\Database\Eloquent\Builder|Occupation newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Occupation newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Occupation query()
 * @method static \Illuminate\Database\Eloquent\Builder|Occupation whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Occupation whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Occupation whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Occupation whereUpdatedAt($value)
 * @mixin \Eloquent
 */
	class IdeHelperOccupation {}
}

namespace App\Models{
/**
 * App\Models\OfficersList
 *
 * @property int $id
 * @property string $year
 * @property array $officers
 * @property \Carbon\CarbonImmutable|null $created_at
 * @property \Carbon\CarbonImmutable|null $updated_at
 * @method static \Illuminate\Database\Eloquent\Builder|OfficersList newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|OfficersList newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|OfficersList query()
 * @method static \Illuminate\Database\Eloquent\Builder|OfficersList whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|OfficersList whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|OfficersList whereOfficers($value)
 * @method static \Illuminate\Database\Eloquent\Builder|OfficersList whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|OfficersList whereYear($value)
 * @mixin \Eloquent
 */
	class IdeHelperOfficersList {}
}

namespace App\Models{
/**
 * App\Models\PatronageStatus
 *
 * @property int $id
 * @property string $name
 * @property \Carbon\CarbonImmutable|null $created_at
 * @property \Carbon\CarbonImmutable|null $updated_at
 * @method static \Illuminate\Database\Eloquent\Builder|PatronageStatus newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|PatronageStatus newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|PatronageStatus query()
 * @method static \Illuminate\Database\Eloquent\Builder|PatronageStatus whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|PatronageStatus whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|PatronageStatus whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|PatronageStatus whereUpdatedAt($value)
 * @mixin \Eloquent
 */
	class IdeHelperPatronageStatus {}
}

namespace App\Models{
/**
 * App\Models\PaymentType
 *
 * @property int $id
 * @property string $name
 * @property \Carbon\CarbonImmutable|null $created_at
 * @property \Carbon\CarbonImmutable|null $updated_at
 * @method static \Illuminate\Database\Eloquent\Builder|PaymentType newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|PaymentType newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|PaymentType query()
 * @method static \Illuminate\Database\Eloquent\Builder|PaymentType whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|PaymentType whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|PaymentType whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|PaymentType whereUpdatedAt($value)
 * @mixin \Eloquent
 */
	class IdeHelperPaymentType {}
}

namespace App\Models{
/**
 * App\Models\Province
 *
 * @property int $id
 * @property int $region_id
 * @property string $name
 * @property-read \App\Models\Region $region
 * @method static \Illuminate\Database\Eloquent\Builder|Province newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Province newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Province query()
 * @method static \Illuminate\Database\Eloquent\Builder|Province whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Province whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Province whereRegionId($value)
 * @mixin \Eloquent
 */
	class IdeHelperProvince {}
}

namespace App\Models{
/**
 * App\Models\Region
 *
 * @property int $id
 * @property string $name
 * @property string $description
 * @method static \Illuminate\Database\Eloquent\Builder|Region newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Region newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Region query()
 * @method static \Illuminate\Database\Eloquent\Builder|Region whereDescription($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Region whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Region whereName($value)
 * @mixin \Eloquent
 */
	class IdeHelperRegion {}
}

namespace App\Models{
/**
 * App\Models\Religion
 *
 * @property int $id
 * @property string $name
 * @property \Carbon\CarbonImmutable|null $created_at
 * @property \Carbon\CarbonImmutable|null $updated_at
 * @method static \Illuminate\Database\Eloquent\Builder|Religion newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Religion newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Religion query()
 * @method static \Illuminate\Database\Eloquent\Builder|Religion whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Religion whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Religion whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Religion whereUpdatedAt($value)
 * @mixin \Eloquent
 */
	class IdeHelperReligion {}
}

namespace App\Models{
/**
 * App\Models\Saving
 *
 * @property int $id
 * @property int $savings_account_id
 * @property int $member_id
 * @property int $payment_type_id
 * @property string $reference_number
 * @property string $amount
 * @property string|null $deposit
 * @property string|null $withdrawal
 * @property string $interest_rate
 * @property string $interest
 * @property \Carbon\CarbonImmutable $transaction_date
 * @property string $balance
 * @property \Carbon\CarbonImmutable|null $interest_date
 * @property bool $accrued
 * @property int|null $cashier_id
 * @property \Carbon\CarbonImmutable|null $created_at
 * @property \Carbon\CarbonImmutable|null $updated_at
 * @property-read \App\Models\User|null $cashier
 * @property-read \App\Models\Member $member
 * @property-read \App\Models\SavingsAccount $savings_account
 * @method static \Illuminate\Database\Eloquent\Builder|Saving newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Saving newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Saving query()
 * @method static \Illuminate\Database\Eloquent\Builder|Saving whereAccrued($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Saving whereAmount($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Saving whereBalance($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Saving whereCashierId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Saving whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Saving whereDeposit($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Saving whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Saving whereInterest($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Saving whereInterestDate($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Saving whereInterestRate($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Saving whereMemberId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Saving wherePaymentTypeId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Saving whereReferenceNumber($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Saving whereSavingsAccountId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Saving whereTransactionDate($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Saving whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Saving whereWithdrawal($value)
 * @mixin \Eloquent
 */
	class IdeHelperSaving {}
}

namespace App\Models{
/**
 * App\Models\SavingsAccount
 *
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
 * @property-read \App\Models\AccountType $account_type
 * @property-read \Staudenmeir\LaravelAdjacencyList\Eloquent\Collection|\App\Models\SavingsAccount[] $children
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
 * @property-read \Staudenmeir\LaravelAdjacencyList\Eloquent\Collection|\App\Models\SavingsAccount[] $ancestors The model's recursive parents.
 * @property-read int|null $ancestors_count
 * @property-read \Staudenmeir\LaravelAdjacencyList\Eloquent\Collection|\App\Models\SavingsAccount[] $ancestorsAndSelf The model's recursive parents and itself.
 * @property-read int|null $ancestors_and_self_count
 * @property-read \Staudenmeir\LaravelAdjacencyList\Eloquent\Collection|\App\Models\SavingsAccount[] $bloodline The model's ancestors, descendants and itself.
 * @property-read int|null $bloodline_count
 * @property-read \Staudenmeir\LaravelAdjacencyList\Eloquent\Collection|\App\Models\SavingsAccount[] $childrenAndSelf The model's direct children and itself.
 * @property-read int|null $children_and_self_count
 * @property-read \Staudenmeir\LaravelAdjacencyList\Eloquent\Collection|\App\Models\SavingsAccount[] $descendants The model's recursive children.
 * @property-read int|null $descendants_count
 * @property-read \Staudenmeir\LaravelAdjacencyList\Eloquent\Collection|\App\Models\SavingsAccount[] $descendantsAndSelf The model's recursive children and itself.
 * @property-read int|null $descendants_and_self_count
 * @property-read \Staudenmeir\LaravelAdjacencyList\Eloquent\Collection|\App\Models\SavingsAccount[] $parentAndSelf The model's direct parent and itself.
 * @property-read int|null $parent_and_self_count
 * @property-read \App\Models\SavingsAccount|null $rootAncestor The model's topmost parent.
 * @property-read \Staudenmeir\LaravelAdjacencyList\Eloquent\Collection|\App\Models\SavingsAccount[] $siblings The parent's other children.
 * @property-read int|null $siblings_count
 * @property-read \Staudenmeir\LaravelAdjacencyList\Eloquent\Collection|\App\Models\SavingsAccount[] $siblingsAndSelf All the parent's children.
 * @property-read int|null $siblings_and_self_count
 * @method static \Staudenmeir\LaravelAdjacencyList\Eloquent\Collection<int, static> all($columns = ['*'])
 * @method static \Staudenmeir\LaravelAdjacencyList\Eloquent\Builder|Account breadthFirst()
 * @method static \Staudenmeir\LaravelAdjacencyList\Eloquent\Builder|Account depthFirst()
 * @method static \Staudenmeir\LaravelAdjacencyList\Eloquent\Builder|Account doesntHaveChildren()
 * @method static \Staudenmeir\LaravelAdjacencyList\Eloquent\Collection<int, static> get($columns = ['*'])
 * @method static \Staudenmeir\LaravelAdjacencyList\Eloquent\Builder|SavingsAccount getExpressionGrammar()
 * @method static \Staudenmeir\LaravelAdjacencyList\Eloquent\Builder|Account hasChildren()
 * @method static \Staudenmeir\LaravelAdjacencyList\Eloquent\Builder|Account hasParent()
 * @method static \Staudenmeir\LaravelAdjacencyList\Eloquent\Builder|Account isLeaf()
 * @method static \Staudenmeir\LaravelAdjacencyList\Eloquent\Builder|Account isRoot()
 * @method static \Staudenmeir\LaravelAdjacencyList\Eloquent\Builder|SavingsAccount newModelQuery()
 * @method static \Staudenmeir\LaravelAdjacencyList\Eloquent\Builder|SavingsAccount newQuery()
 * @method static \Staudenmeir\LaravelAdjacencyList\Eloquent\Builder|SavingsAccount query()
 * @method static \Staudenmeir\LaravelAdjacencyList\Eloquent\Builder|Account tree($maxDepth = null)
 * @method static \Staudenmeir\LaravelAdjacencyList\Eloquent\Builder|Account treeOf(\Illuminate\Database\Eloquent\Model|callable $constraint, $maxDepth = null)
 * @method static \Staudenmeir\LaravelAdjacencyList\Eloquent\Builder|SavingsAccount whereAccountTypeId($value)
 * @method static \Staudenmeir\LaravelAdjacencyList\Eloquent\Builder|SavingsAccount whereAccountableId($value)
 * @method static \Staudenmeir\LaravelAdjacencyList\Eloquent\Builder|SavingsAccount whereAccountableType($value)
 * @method static \Staudenmeir\LaravelAdjacencyList\Eloquent\Builder|SavingsAccount whereCreatedAt($value)
 * @method static \Staudenmeir\LaravelAdjacencyList\Eloquent\Builder|Account whereDepth($operator, $value = null)
 * @method static \Staudenmeir\LaravelAdjacencyList\Eloquent\Builder|SavingsAccount whereId($value)
 * @method static \Staudenmeir\LaravelAdjacencyList\Eloquent\Builder|SavingsAccount whereMemberId($value)
 * @method static \Staudenmeir\LaravelAdjacencyList\Eloquent\Builder|SavingsAccount whereName($value)
 * @method static \Staudenmeir\LaravelAdjacencyList\Eloquent\Builder|SavingsAccount whereNumber($value)
 * @method static \Staudenmeir\LaravelAdjacencyList\Eloquent\Builder|SavingsAccount whereParentId($value)
 * @method static \Staudenmeir\LaravelAdjacencyList\Eloquent\Builder|SavingsAccount whereTag($value)
 * @method static \Staudenmeir\LaravelAdjacencyList\Eloquent\Builder|SavingsAccount whereUpdatedAt($value)
 * @method static \Staudenmeir\LaravelAdjacencyList\Eloquent\Builder|Account withCode()
 * @method static \Staudenmeir\LaravelAdjacencyList\Eloquent\Builder|SavingsAccount withGlobalScopes(array $scopes)
 * @method static \Staudenmeir\LaravelAdjacencyList\Eloquent\Builder|Account withRelationshipExpression($direction, callable $constraint, $initialDepth, $from = null, $maxDepth = null)
 * @mixin \Eloquent
 */
	class IdeHelperSavingsAccount {}
}

namespace App\Models{
/**
 * App\Models\SystemConfiguration
 *
 * @property int $id
 * @property string $name
 * @property array $content
 * @property \Carbon\CarbonImmutable|null $created_at
 * @property \Carbon\CarbonImmutable|null $updated_at
 * @method static \Illuminate\Database\Eloquent\Builder|SystemConfiguration newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|SystemConfiguration newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|SystemConfiguration query()
 * @method static \Illuminate\Database\Eloquent\Builder|SystemConfiguration whereContent($value)
 * @method static \Illuminate\Database\Eloquent\Builder|SystemConfiguration whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|SystemConfiguration whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|SystemConfiguration whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|SystemConfiguration whereUpdatedAt($value)
 * @mixin \Eloquent
 */
	class IdeHelperSystemConfiguration {}
}

namespace App\Models{
/**
 * App\Models\TimeDeposit
 *
 * @property int $id
 * @property int $member_id
 * @property int $payment_type_id
 * @property string $reference_number
 * @property string|null $identifier
 * @property \Carbon\CarbonImmutable $maturity_date
 * @property string|null $withdrawal_date
 * @property string $amount
 * @property int $number_of_days
 * @property string $maturity_amount
 * @property string $interest_rate
 * @property string|null $interest
 * @property \Carbon\CarbonImmutable $transaction_date
 * @property string $tdc_number
 * @property string $account_number
 * @property int|null $cashier_id
 * @property \Carbon\CarbonImmutable|null $created_at
 * @property \Carbon\CarbonImmutable|null $updated_at
 * @property-read \App\Models\User|null $cashier
 * @property-read \App\Models\Member $member
 * @method static \Illuminate\Database\Eloquent\Builder|TimeDeposit newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|TimeDeposit newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|TimeDeposit query()
 * @method static \Illuminate\Database\Eloquent\Builder|TimeDeposit whereAccountNumber($value)
 * @method static \Illuminate\Database\Eloquent\Builder|TimeDeposit whereAmount($value)
 * @method static \Illuminate\Database\Eloquent\Builder|TimeDeposit whereCashierId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|TimeDeposit whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|TimeDeposit whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|TimeDeposit whereIdentifier($value)
 * @method static \Illuminate\Database\Eloquent\Builder|TimeDeposit whereInterest($value)
 * @method static \Illuminate\Database\Eloquent\Builder|TimeDeposit whereInterestRate($value)
 * @method static \Illuminate\Database\Eloquent\Builder|TimeDeposit whereMaturityAmount($value)
 * @method static \Illuminate\Database\Eloquent\Builder|TimeDeposit whereMaturityDate($value)
 * @method static \Illuminate\Database\Eloquent\Builder|TimeDeposit whereMemberId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|TimeDeposit whereNumberOfDays($value)
 * @method static \Illuminate\Database\Eloquent\Builder|TimeDeposit wherePaymentTypeId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|TimeDeposit whereReferenceNumber($value)
 * @method static \Illuminate\Database\Eloquent\Builder|TimeDeposit whereTdcNumber($value)
 * @method static \Illuminate\Database\Eloquent\Builder|TimeDeposit whereTransactionDate($value)
 * @method static \Illuminate\Database\Eloquent\Builder|TimeDeposit whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|TimeDeposit whereWithdrawalDate($value)
 * @mixin \Eloquent
 */
	class IdeHelperTimeDeposit {}
}

namespace App\Models{
/**
 * App\Models\Transaction
 *
 * @property int $id
 * @property int $transaction_type_id
 * @property int $account_id
 * @property int|null $member_id
 * @property string $reference_number
 * @property string|null $remarks
 * @property string|null $credit
 * @property string|null $debit
 * @property string $transaction_date
 * @property \Carbon\CarbonImmutable|null $created_at
 * @property \Carbon\CarbonImmutable|null $updated_at
 * @property-read \App\Models\Account $account
 * @property-read \App\Models\TransactionType $transaction_type
 * @method static \Illuminate\Database\Eloquent\Builder|Transaction newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Transaction newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Transaction query()
 * @method static \Illuminate\Database\Eloquent\Builder|Transaction whereAccountId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Transaction whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Transaction whereCredit($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Transaction whereDebit($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Transaction whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Transaction whereMemberId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Transaction whereReferenceNumber($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Transaction whereRemarks($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Transaction whereTransactionDate($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Transaction whereTransactionTypeId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Transaction whereUpdatedAt($value)
 * @mixin \Eloquent
 */
	class IdeHelperTransaction {}
}

namespace App\Models{
/**
 * App\Models\TransactionType
 *
 * @property int $id
 * @property string $name
 * @property \Carbon\CarbonImmutable|null $created_at
 * @property \Carbon\CarbonImmutable|null $updated_at
 * @method static \Illuminate\Database\Eloquent\Builder|TransactionType newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|TransactionType newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|TransactionType query()
 * @method static \Illuminate\Database\Eloquent\Builder|TransactionType whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|TransactionType whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|TransactionType whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|TransactionType whereUpdatedAt($value)
 * @mixin \Eloquent
 */
	class IdeHelperTransactionType {}
}

namespace App\Models{
/**
 * App\Models\User
 *
 * @property int $id
 * @property string $name
 * @property string $email
 * @property \Carbon\CarbonImmutable|null $email_verified_at
 * @property mixed $password
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
 * @property-read \Illuminate\Notifications\DatabaseNotificationCollection<int, \Illuminate\Notifications\DatabaseNotification> $notifications
 * @property-read int|null $notifications_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \Spatie\Permission\Models\Permission> $permissions
 * @property-read int|null $permissions_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \Spatie\Permission\Models\Role> $roles
 * @property-read int|null $roles_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \Laravel\Sanctum\PersonalAccessToken> $tokens
 * @property-read int|null $tokens_count
 * @method static \Database\Factories\UserFactory factory($count = null, $state = [])
 * @method static \Illuminate\Database\Eloquent\Builder|User newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|User newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|User permission($permissions)
 * @method static \Illuminate\Database\Eloquent\Builder|User query()
 * @method static \Illuminate\Database\Eloquent\Builder|User role($roles, $guard = null)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereEmail($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereEmailVerifiedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User wherePassword($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereRememberToken($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereUpdatedAt($value)
 * @mixin \Eloquent
 */
	class IdeHelperUser {}
}

