<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        // Fix existing data violations before adding constraints
        DB::statement('UPDATE loans SET outstanding_balance = 0 WHERE outstanding_balance < 0');

        // Loans constraints
        DB::statement('ALTER TABLE loans ADD CONSTRAINT loans_gross_amount_positive CHECK (gross_amount > 0)');
        DB::statement('ALTER TABLE loans ADD CONSTRAINT loans_outstanding_balance_non_negative CHECK (outstanding_balance >= 0)');
        DB::statement('ALTER TABLE loans ADD CONSTRAINT loans_number_of_terms_positive CHECK (number_of_terms > 0)');

        // Loan types constraints
        DB::statement('ALTER TABLE loan_types ADD CONSTRAINT loan_types_interest_rate_non_negative CHECK (interest_rate >= 0)');
        DB::statement('ALTER TABLE loan_types ADD CONSTRAINT loan_types_max_amount_positive CHECK (max_amount > 0)');
    }

    public function down(): void
    {
        DB::statement('ALTER TABLE loans DROP CHECK loans_gross_amount_positive');
        DB::statement('ALTER TABLE loans DROP CHECK loans_outstanding_balance_non_negative');
        DB::statement('ALTER TABLE loans DROP CHECK loans_number_of_terms_positive');

        DB::statement('ALTER TABLE loan_types DROP CHECK loan_types_interest_rate_non_negative');
        DB::statement('ALTER TABLE loan_types DROP CHECK loan_types_max_amount_positive');
    }
};
