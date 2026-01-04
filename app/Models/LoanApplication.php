<?php

namespace App\Models;

use App\Oxytoxin\DTO\Loan\LoanApproval;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use NumberFormatter;
use Spatie\LaravelData\DataCollection;

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
class LoanApplication extends Model
{
    use HasFactory;

    const STATUS_PROCESSING = 1;

    const STATUS_APPROVED = 2;

    const STATUS_DISAPPROVED = 3;

    const STATUS_POSTED = 4;

    protected $casts = [
        'number_of_terms' => 'integer',
        'desired_amount' => 'decimal:4',
        'cbu_amount' => 'decimal:4',
        'monthly_payment' => 'decimal:4',
        'transaction_date' => 'immutable_date',
        'disapproval_date' => 'immutable_date',
        'approval_date' => 'immutable_date',
        'payment_start_date' => 'immutable_date',
        'surcharge_start_date' => 'immutable_date',
        'status' => 'integer',
        'approvals' => DataCollection::class.':'.LoanApproval::class,
    ];

    public function getStatusNameAttribute()
    {
        return match ($this->status) {
            self::STATUS_PROCESSING => 'On Process',
            self::STATUS_APPROVED => 'On Process',
            self::STATUS_DISAPPROVED => 'Disapproved',
            self::STATUS_POSTED => 'Posted',
            default => 'processing'
        };
    }

    public function desiredAmountInWords(): Attribute
    {
        return Attribute::make(get: fn () => (new NumberFormatter('en', NumberFormatter::SPELLOUT))->format($this->desired_amount));
    }

    public function disapproval_reason()
    {
        return $this->belongsTo(DisapprovalReason::class);
    }

    public function member()
    {
        return $this->belongsTo(Member::class);
    }

    public function comakers()
    {
        return $this->hasMany(LoanApplicationComaker::class);
    }

    public function processor()
    {
        return $this->belongsTo(User::class, 'processor_id');
    }

    public function loan()
    {
        return $this->hasOne(Loan::class);
    }

    public function loan_type()
    {
        return $this->belongsTo(LoanType::class);
    }

    protected static function booted(): void
    {
        static::creating(function (LoanApplication $loanApplication) {
            $crecom_secretary = User::whereRelation('roles', 'name', 'crecom-secretary')->first();
            $crecom_vicechairperson = User::whereRelation('roles', 'name', 'crecom-vicechairperson')->first();
            $crecom_chairperson = User::whereRelation('roles', 'name', 'crecom-chairperson')->first();
            $approvals = [];
            if ($crecom_secretary) {
                $approvals[] = (new LoanApproval($crecom_secretary->name, 'CRECOM-Secretary'));
            }
            if ($crecom_chairperson) {
                $approvals[] = (new LoanApproval($crecom_chairperson->name, 'CRECOM-Chairperson'));
            }
            if ($crecom_vicechairperson) {
                $approvals[] = (new LoanApproval($crecom_vicechairperson->name, 'CRECOM-Vice Chairperson'));
            }

            if ($loanApplication->desired_amount > 50000) {
                $bod_chairperson = User::whereRelation('roles', 'name', 'bod-chairperson')->first();
                $approvals[] = (new LoanApproval($bod_chairperson->name, 'BOD-Chairperson'));
            } else {
                $manager = User::whereRelation('roles', 'name', 'manager')->first();
                $approvals[] = (new LoanApproval($manager->name, 'Manager'));
            }
            $loanApplication->processor_id = auth()->id();
            $loanApplication->approvals = $approvals;
            $loanApplication->cbu_amount = CapitalSubscription::whereMemberId($loanApplication->member_id)->sum('actual_amount_paid');
        });

        static::created(function (LoanApplication $loanApplication) {
            $loanApplication->reference_number = $loanApplication->loan_type->code.'-'.(config('app.transaction_date') ?? today())->format('Y-').str_pad($loanApplication->id, 6, '0', STR_PAD_LEFT);
            $loanApplication->save();
        });
    }
}
