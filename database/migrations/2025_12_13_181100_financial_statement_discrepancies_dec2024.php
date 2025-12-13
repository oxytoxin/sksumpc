<?php

    use App\Models\Transaction;
    use Illuminate\Database\Migrations\Migration;
    use Illuminate\Database\Schema\Blueprint;
    use Illuminate\Support\Facades\Schema;

    return new class extends Migration {
        public function up(): void
        {
            // Resolve rounding errors amounting to 0.0071, added to PAID CBU of MILDRED ACCAD and JULIE ALBANO
            // This should not have any effect on actual payments as rounding will yield the same amount.
            Transaction::find(8)->update(['credit' => 274574.0430]);
            Transaction::find(12)->update(['credit' => 230930.3045]);
        }

    };
