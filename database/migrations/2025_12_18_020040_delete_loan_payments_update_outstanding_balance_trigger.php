<?php

    use Illuminate\Database\Migrations\Migration;
    use Illuminate\Database\Schema\Blueprint;
    use Illuminate\Support\Facades\Schema;

    return new class extends Migration {
        public function up(): void
        {
            DB::unprepared('DROP TRIGGER IF EXISTS update_loan_outstanding_balance_on_new_payment');
        }
    };
