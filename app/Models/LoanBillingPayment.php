<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

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
class LoanBillingPayment extends Model
{
    use HasFactory;

    protected $casts = [
        'posted' => 'boolean',
        'amount_due' => 'decimal:4',
        'amount_paid' => 'decimal:4',
    ];

    public function loan_billing()
    {
        return $this->belongsTo(LoanBilling::class);
    }

    public function loan()
    {
        return $this->belongsTo(Loan::class);
    }

    public function member()
    {
        return $this->belongsTo(Member::class);
    }
}
