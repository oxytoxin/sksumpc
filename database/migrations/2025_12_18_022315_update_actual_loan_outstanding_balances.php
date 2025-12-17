<?php

    use App\Models\Loan;
    use Illuminate\Database\Migrations\Migration;
    use Illuminate\Database\Schema\Blueprint;
    use Illuminate\Support\Facades\Schema;

    return new class extends Migration {
        public function up(): void
        {
            $loans = Loan::get();

            foreach ($loans as $key => $loan) {
                $current = $key + 1;
                dump("{$current}/{$loans->count()} done...");
                $loan->update(['outstanding_balance' => $loan->gross_amount - $loan->payments()->sum('principal_payment')]);
            }
        }
    };
