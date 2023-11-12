<?php

namespace App\Models;

use App\Oxytoxin\DTO\LoanApproval;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Spatie\LaravelData\DataCollection;
use Str;

class LoanApplication extends Model
{
    use HasFactory;

    const STATUS_PROCESSING = 1;
    const STATUS_APPROVED = 2;
    const STATUS_DISAPPROVED = 3;

    protected $casts = [
        'number_of_terms' => 'integer',
        'desired_amount' => 'decimal:2',
        'monthly_payment' => 'decimal:4',
        'transaction_date' => 'immutable_date',
        'status' => 'integer',
        'approvals' => DataCollection::class . ':' . LoanApproval::class,
    ];

    public function getApprovalListAttribute()
    {
        return  collect($this->approvals->items())->map(fn ($a) => $a->position . ': ' . match ($a->approved) {
            true => 'Approved',
            false => 'Disapproved',
            null => 'Pending',
        })->toArray();
    }

    public function member()
    {
        return $this->belongsTo(Member::class);
    }

    public function processor()
    {
        return $this->belongsTo(User::class, 'processor_id');
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
                $approvals[] = (new LoanApproval($crecom_secretary->id, 'Crecom-Secretary'));
            }
            if ($crecom_vicechairperson) {
                $approvals[] = (new LoanApproval($crecom_vicechairperson->id, 'Crecom-Vice Chairperson'));
            }
            if ($crecom_chairperson) {
                $approvals[] = (new LoanApproval($crecom_chairperson->id, 'Crecom-Chairperson'));
            }
            if ($loanApplication->desired_amount > 50000) {
                $bod_chairperson = User::whereRelation('roles', 'name', 'bod-chairperson')->first();
                $approvals[] = (new LoanApproval($bod_chairperson->id, 'BOD-Chairperson'));
            } else {
                $manager = User::whereRelation('roles', 'name', 'manager')->first();
                $approvals[] = (new LoanApproval($manager->id, 'Manager'));
            }
            $loanApplication->processor_id  = auth()->id();
            $loanApplication->approvals = $approvals;
        });

        static::created(function (LoanApplication $loanApplication) {
            $loanApplication->reference_number  = $loanApplication->loan_type->code . '-' . today()->format('Y-') . str_pad($loanApplication->id, 6, '0', STR_PAD_RIGHT);
            $loanApplication->save();
        });

        static::updating(function (LoanApplication $loanApplication) {
            if (
                !count($loanApplication->approvals->where('approved', null)) &&
                !count($loanApplication->approvals->where('approved', false))
            ) {
                $loanApplication->status = LoanApplication::STATUS_APPROVED;
            }
        });
    }
}
