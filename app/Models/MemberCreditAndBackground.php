<?php

    namespace App\Models;

    use App\Casts\ChildrenCast;
    use App\Casts\EmploymentVerificationCast;
    use App\Casts\IncomeVerificationCast;
    use App\Casts\SpouseCast;
    use Illuminate\Database\Eloquent\Model;
    use Illuminate\Database\Eloquent\Relations\BelongsTo;

    class MemberCreditAndBackground extends Model
    {
        protected $table = 'member_credit_and_backgrounds';

        protected $casts = [
            'spouse' => SpouseCast::class,
            'children' => ChildrenCast::class,
            'employment_verification' => EmploymentVerificationCast::class,
            'income_verification' => IncomeVerificationCast::class,
        ];

        public function member(): BelongsTo
        {
            return $this->belongsTo(Member::class);
        }
    }
