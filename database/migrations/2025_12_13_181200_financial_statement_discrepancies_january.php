<?php

    use App\Models\Transaction;
    use Illuminate\Database\Migrations\Migration;
    use Illuminate\Database\Schema\Blueprint;
    use Illuminate\Support\Facades\Schema;

    return new class extends Migration {
        public function up(): void
        {
            Transaction::find(2944)->update(['credit' => 177.3562]);
            Transaction::find(3050)->update(['credit' => 461.5378]);
            Transaction::find(3272)->update(['credit' => 1677.5931]);
            Transaction::find(3275)->update(['credit' => 2001.6806]);
            Transaction::find(3290)->update(['credit' => 243.6512]);
            Transaction::find(3296)->update(['credit' => 517.3738]);
            Transaction::find(3299)->update(['credit' => 530.9548]);
            Transaction::find(3302)->update(['credit' => 1040.6584]);
            Transaction::find(3308)->update(['credit' => 651.1677]);
            Transaction::find(3314)->update(['credit' => 137.4159]);
            Transaction::find(4333)->update(['credit' => 40.1230]);
        }


    };
