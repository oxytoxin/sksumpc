<?php

namespace App\Models;

use App\Oxytoxin\DTO\LoanApproval;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Spatie\LaravelData\DataCollection;
use Str;

/**
 * @mixin IdeHelperLoanApplication
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
        'monthly_payment' => 'decimal:4',
        'transaction_date' => 'immutable_date',
        'disapproval_date' => 'immutable_date',
        'status' => 'integer',
        'approvals' => DataCollection::class . ':' . LoanApproval::class,
    ];

    public function disapproval_reason()
    {
        return $this->belongsTo(DisapprovalReason::class);
    }

    public function member()
    {
        return $this->belongsTo(Member::class);
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
                $approvals[] = (new LoanApproval($crecom_secretary->name, 'Crecom-Secretary'));
            }
            if ($crecom_chairperson) {
                $approvals[] = (new LoanApproval($crecom_chairperson->name, 'Crecom-Chairperson'));
            }
            if ($crecom_vicechairperson) {
                $approvals[] = (new LoanApproval($crecom_vicechairperson->name, 'Crecom-Vice Chairperson'));
            }

            if ($loanApplication->desired_amount > 50000) {
                $bod_chairperson = User::whereRelation('roles', 'name', 'bod-chairperson')->first();
                $approvals[] = (new LoanApproval($bod_chairperson->name, 'BOD-Chairperson'));
            } else {
                $manager = User::whereRelation('roles', 'name', 'manager')->first();
                $approvals[] = (new LoanApproval($manager->name, 'Manager'));
            }
            $loanApplication->processor_id  = auth()->id();
            $loanApplication->approvals = $approvals;
        });

        static::created(function (LoanApplication $loanApplication) {
            $loanApplication->reference_number  = $loanApplication->loan_type->code . '-' . today()->format('Y-') . str_pad($loanApplication->id, 6, '0', STR_PAD_LEFT);
            $loanApplication->save();
        });
    }
}
