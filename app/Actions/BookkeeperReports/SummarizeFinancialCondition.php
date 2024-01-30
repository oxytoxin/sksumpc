<?php

namespace App\Actions\BookkeeperReports;

use App\Models\BalanceForwardedSummary;
use Carbon\CarbonImmutable;
use Lorisleiva\Actions\Concerns\AsAction;

class SummarizeFinancialCondition
{
    use AsAction;

    public function handle($month = null, $year = null)
    {
        $current_date = CarbonImmutable::create(month: $month, year: $year)->endOfMonth();
        $previous_date = $current_date->subMonthNoOverflow();

        $current = app(SummarizeTrialBalanceReport::class)->handle(month: 1, year: 2024);
    }
}
