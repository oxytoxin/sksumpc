<?php

namespace App\Models;

use DB;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * @mixin IdeHelperLoanBilling
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
        return Attribute::make(get: fn() => filled($this->or_number));
    }

    protected static function booted(): void
    {
        static::created(function (LoanBilling $loanBilling) {
            DB::beginTransaction();
            $loanBilling->reference_number = $loanBilling->loan_type->code . '-' . (config('app.transaction_date') ?? today())->format('Y-m-') . str_pad($loanBilling->id, 6, '0', STR_PAD_LEFT);
            Loan::wherePosted(true)
                ->where('outstanding_balance', '>', 1)
                ->whereLoanTypeId($loanBilling->loan_type_id)
                ->when($loanBilling->member_type_id, fn($query, $value) => $query->whereRelation('member', 'member_type_id', $value))
                ->when($loanBilling->member_subtype_id, fn($query, $value) => $query->whereRelation('member', 'member_subtype_id', $value))
                ->each(function ($loan) use ($loanBilling) {
                    LoanBillingPayment::firstOrCreate([
                        'member_id' => $loan->member_id,
                        'loan_billing_id' => $loanBilling->id,
                    ], [
                        'loan_id' => $loan->id,
                        'amount_due' => $loan->outstanding_balance,
                        'amount_paid' => $loan->monthly_payment,
                    ]);
                });
            $loanBilling->cashier_id = auth()->id();
            $loanBilling->save();
            DB::commit();
        });
    }
}
