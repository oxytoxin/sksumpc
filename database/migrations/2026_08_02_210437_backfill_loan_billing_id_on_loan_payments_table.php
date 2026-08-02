<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        DB::statement(<<<'SQL'
            UPDATE loan_payments lp
            INNER JOIN loan_billings lb
                ON lb.or_number = lp.reference_number
                AND lb.posted = 1
                AND lb.or_number IS NOT NULL
                AND lp.transaction_date = COALESCE(lb.or_date, lb.date)
            INNER JOIN loans l
                ON l.id = lp.loan_id
                AND l.loan_type_id = lb.loan_type_id
            SET lp.loan_billing_id = lb.id
            WHERE lp.loan_billing_id IS NULL
        SQL);
    }
};
