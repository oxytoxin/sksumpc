<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('loans', function (Blueprint $table) {
            $table->decimal('outstanding_balance', 18, 4)->after('monthly_payment');
        });
        DB::unprepared('
            CREATE TRIGGER update_loan_outstanding_balance_on_new_payment
            BEFORE INSERT ON loan_payments
            FOR EACH ROW
            BEGIN
                UPDATE loans
                SET outstanding_balance = (SELECT outstanding_balance FROM loans WHERE id = NEW.loan_id) - NEW.principal_payment
                WHERE id = NEW.loan_id;
            END;
            CREATE TRIGGER update_loan_outstanding_balance_on_revert_payment
            BEFORE DELETE ON loan_payments
            FOR EACH ROW
            BEGIN
                UPDATE loans
                SET outstanding_balance = (SELECT outstanding_balance FROM loans WHERE id = OLD.loan_id) + OLD.principal_payment
                WHERE id = OLD.loan_id;
            END;
        ');
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('capital_subscriptions', function (Blueprint $table) {
            //
        });
    }
};
