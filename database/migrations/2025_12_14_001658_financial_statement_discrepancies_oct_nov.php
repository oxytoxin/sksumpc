<?php

    use App\Models\Transaction;
    use Illuminate\Database\Migrations\Migration;
    use Illuminate\Database\Schema\Blueprint;
    use Illuminate\Support\Facades\Schema;

    return new class extends Migration {
        public function up(): void
        {
            Transaction::find(57255)->update(['credit' => 7435.3012]);
            Transaction::find(57263)->update(['credit' => 1442.923]);
            Transaction::find(62223)->update(['credit' => 180.3968]);
        }
    };
