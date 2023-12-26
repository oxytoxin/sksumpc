<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        DB::unprepared('
            CREATE TRIGGER update_imprests_balance BEFORE INSERT ON imprests
            FOR EACH ROW
            BEGIN
                DECLARE total_balance DECIMAL(14, 2);

                SELECT COALESCE(SUM(amount), 0) INTO total_balance
                FROM imprests
                WHERE member_id = NEW.member_id;

                SET NEW.balance = total_balance + NEW.amount;
            END;
        ');
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
    }
};
