<?php

    use App\Models\Transaction;
    use Illuminate\Database\Migrations\Migration;
    use Illuminate\Database\Schema\Blueprint;
    use Illuminate\Support\Facades\Schema;

    return new class extends Migration {
        public function up(): void
        {
            // JULY
            Transaction::find(42026)->update(['credit' => 4573.683]);
            Transaction::find(42035)->update(['credit' => 1226.959]);
            Transaction::find(42050)->update(['credit' => 1591.4847]);
            Transaction::find(42191)->update(['credit' => 3615.347]);
            Transaction::find(42305)->update(['credit' => 260.447]);

            // AUGUST
            Transaction::find(50375)->update(['credit' => 1529.368]);
        }
    };
