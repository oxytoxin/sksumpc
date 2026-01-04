<?php

namespace App\Models;

use DB;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

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
class LoanBilling extends Model
{
    use HasFactory;

    protected $casts = [
        'date' => 'immutable_date',
        'or_date' => 'immutable_date',
        'posted' => 'boolean',
        'for_or' => 'boolean',
    ];

    public function loan_billing_payments()
    {
        return $this->hasMany(LoanBillingPayment::class);
    }

    public function payment_type()
    {
        return $this->belongsTo(PaymentType::class);
    }

    public function loan_type()
    {
        return $this->belongsTo(LoanType::class);
    }

    public function cashier()
    {
        return $this->belongsTo(User::class, 'cashier_id');
    }

    public function OrApproved(): Attribute
    {
        return Attribute::make(get: fn () => filled($this->or_number));
    }

    public function CanForOr(): Attribute
    {
        return Attribute::make(get: fn ($value) => ! $this->for_or && ! $this->or_number && ! $this->posted);
    }

    public function CanPostPayments(): Attribute
    {
        return Attribute::make(get: fn () => ! $this->posted && ! $this->for_or && $this->or_number);
    }

    protected static function booted(): void
    {
        static::created(function (LoanBilling $loanBilling) {
            DB::beginTransaction();
            $loanBilling->reference_number = $loanBilling->loan_type->code.'-'.(config('app.transaction_date') ?? today())->format('Y-m-').str_pad($loanBilling->id, 6, '0', STR_PAD_LEFT);
            Loan::wherePosted(true)
                ->where('outstanding_balance', '>', 1)
                ->whereLoanTypeId($loanBilling->loan_type_id)
                ->when($loanBilling->member_type_id, fn ($query, $value) => $query->whereRelation('member', 'member_type_id', $value))
                ->when($loanBilling->member_subtype_id, fn ($query, $value) => $query->whereRelation('member', 'member_subtype_id', $value))
                ->each(function ($loan) use ($loanBilling) {
                    $amount_paid = min($loan->outstanding_balance, $loan->monthly_payment);
                    LoanBillingPayment::firstOrCreate([
                        'member_id' => $loan->member_id,
                        'loan_billing_id' => $loanBilling->id,
                    ], [
                        'loan_id' => $loan->id,
                        'amount_due' => $loan->outstanding_balance,
                        'amount_paid' => $amount_paid,
                    ]);
                });
            $loanBilling->cashier_id = auth()->id();
            $loanBilling->save();
            DB::commit();
        });
    }
}
