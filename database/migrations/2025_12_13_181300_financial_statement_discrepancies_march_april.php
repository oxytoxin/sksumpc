<?php

    use App\Models\Transaction;
    use Illuminate\Database\Migrations\Migration;
    use Illuminate\Database\Schema\Blueprint;
    use Illuminate\Support\Facades\Schema;

    return new class extends Migration {
        public function up(): void
        {
            // MARCH
            Transaction::find(16702)->update(['credit' => 1735.0371]);
            // APRIL
            Transaction::find(23984)->update(['credit' => 17829.78]);
            Transaction::find(24777)->update(['credit' => 2681.7836]);
        }
    };
