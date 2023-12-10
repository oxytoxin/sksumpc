<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        DB::unprepared('
            CREATE TRIGGER update_savings_balance BEFORE INSERT ON savings
            FOR EACH ROW
            BEGIN
                DECLARE total_balance DECIMAL(14, 2);

                SELECT COALESCE(SUM(amount), 0) INTO total_balance
                FROM savings
                WHERE savings_account_id = NEW.savings_account_id;

                SET NEW.balance = total_balance + NEW.amount;

                UPDATE savings_accounts
                SET balance = total_balance + NEW.amount
                WHERE savings_accounts.id = NEW.savings_account_id;
            END;
        ');
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('savings', function (Blueprint $table) {
            //
        });
    }
};
