<?php

    use App\Models\Transaction;
    use Illuminate\Database\Migrations\Migration;
    use Illuminate\Database\Schema\Blueprint;
    use Illuminate\Support\Facades\Schema;

    return new class extends Migration {
        public function up(): void
        {
            // Invalid transactions, loan already paid SUSAN LOSANES
            Transaction::whereIn('id', [51114, 51115, 51116, 51117, 51118, 51119])->delete();

            Transaction::find(54164)->update(['credit' => 1651.9997]);

        }
    };
