<?php

namespace App\Actions\Loans;

use App\Models\Loan;
use Lorisleiva\Actions\Concerns\AsAction;

class ApproveLoanPosting
{
    use AsAction;

    public function handle(Loan $loan)
    {
        $loan->update([
            'posted' => true,
        ]);
    }
}
