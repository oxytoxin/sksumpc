<?php

    use Illuminate\Database\Migrations\Migration;
    use Illuminate\Database\Schema\Blueprint;
    use Illuminate\Support\Facades\Schema;

    return new class extends Migration {
        public function up(): void
        {
            DB::table('loans')
                ->update([
                    'interest' => DB::raw('ROUND(interest, 2)'),
                    'monthly_payment' => DB::raw('ROUND(monthly_payment, 2)'),
                    'outstanding_balance' => DB::raw('ROUND(outstanding_balance, 2)'),
                ]);
        }
    };
