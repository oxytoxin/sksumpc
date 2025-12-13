<?php

    use App\Models\Loan;
    use App\Models\LoanPayment;
    use App\Models\Transaction;
    use Illuminate\Database\Migrations\Migration;
    use Illuminate\Database\Schema\Blueprint;
    use Illuminate\Support\Facades\Schema;

    return new class extends Migration {
        public function up(): void
        {
            Transaction::find(28851)->update(['credit' => 24557.25]);
            Transaction::find(30468)->update(['credit' => 415.5937]);
            Transaction::find(31011)->update(['credit' => 1610.0463]);
            Transaction::find(31524)->update(['credit' => 2006.989]);
            // error due to negative interest payment in loan, need to follow the actual amount in cash on hand/bank
            Transaction::find(37000)->update(['credit' => 1412.2000]);

        }

    };
