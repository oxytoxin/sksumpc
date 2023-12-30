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
            CREATE TRIGGER update_savings_balance 
            BEFORE INSERT ON savings
            FOR EACH ROW
            BEGIN
                DECLARE total_balance DECIMAL(18, 4);

                SELECT COALESCE(SUM(amount), 0) INTO total_balance
                FROM savings
                WHERE savings_account_id = NEW.savings_account_id;

                SET NEW.balance = total_balance + NEW.amount;
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
