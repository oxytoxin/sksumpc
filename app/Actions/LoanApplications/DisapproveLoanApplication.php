<?php

namespace App\Actions\LoanApplications;

use App\Models\LoanApplication;
use Lorisleiva\Actions\Concerns\AsAction;

class DisapproveLoanApplication
{
    use AsAction;

    public function handle(LoanApplication $loan_application, ?string $priority_number, int $disapproval_reason_id, ?string $remarks = null, $disapproval_date = null)
    {
        $loan_application->update([
            'priority_number' => $priority_number,
            'status' => LoanApplication::STATUS_DISAPPROVED,
            'disapproval_date' => $disapproval_date ?? today(),
            'remarks' => $remarks,
        ]);
    }
}
