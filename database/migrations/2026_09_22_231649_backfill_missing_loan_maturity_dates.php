<?php

use App\Models\Loan;
use App\Oxytoxin\Providers\LoansProvider;
use Illuminate\Database\Migrations\Migration;

return new class extends Migration
{
    public function up(): void
    {
        Loan::query()
            ->whereNull('maturity_date')
            ->eachById(function (Loan $loan): void {
                Loan::withoutEvents(fn () => $loan->update([
                    'maturity_date' => LoansProvider::calculateMaturityDate($loan),
                ]));
            });
    }

    public function down(): void {}
};
