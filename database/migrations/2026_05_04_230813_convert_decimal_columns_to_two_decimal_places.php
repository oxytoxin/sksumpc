<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    private function roundColumns(string $table, array $columns): void
    {
        foreach ($columns as $column) {
            DB::statement("UPDATE `{$table}` SET `{$column}` = ROUND(`{$column}`, 2) WHERE `{$column}` != ROUND(`{$column}`, 2)");
        }
    }

    public function up(): void
    {
        // Tables already migrated (balance_forwarded_entries through cash_collectible_subscriptions)
        // are skipped — DDL auto-committed in MySQL before the failure point.

        $this->roundColumns('disbursement_voucher_items', ['credit', 'debit']);
        Schema::table('disbursement_voucher_items', function (Blueprint $table) {
            $table->decimal('credit', 18, 2)->nullable()->change();
            $table->decimal('debit', 18, 2)->nullable()->change();
        });

        $this->roundColumns('imprests', ['amount', 'interest', 'balance']);
        Schema::table('imprests', function (Blueprint $table) {
            $table->decimal('amount', 18, 2)->change();
            $table->dropColumn(['deposit', 'withdrawal']);
            $table->decimal('interest', 18, 2)->change();
            $table->decimal('balance', 18, 2)->change();
        });
        Schema::table('imprests', function (Blueprint $table) {
            $table->decimal('deposit', 18, 2)->virtualAs('if(amount >= 0, amount, null)');
            $table->decimal('withdrawal', 18, 2)->virtualAs('if(amount < 0, amount * -1, null)');
        });

        $this->roundColumns('journal_entry_voucher_items', ['credit', 'debit']);
        Schema::table('journal_entry_voucher_items', function (Blueprint $table) {
            $table->decimal('credit', 18, 2)->nullable()->change();
            $table->decimal('debit', 18, 2)->nullable()->change();
        });

        $this->roundColumns('loan_applications', ['desired_amount', 'cbu_amount', 'monthly_payment']);
        Schema::table('loan_applications', function (Blueprint $table) {
            $table->decimal('desired_amount', 18, 2)->change();
            $table->decimal('cbu_amount', 18, 2)->change();
            $table->decimal('monthly_payment', 16, 2)->change();
        });

        $this->roundColumns('loan_billing_payments', ['amount_due', 'amount_paid']);
        Schema::table('loan_billing_payments', function (Blueprint $table) {
            $table->decimal('amount_due', 18, 2)->change();
            $table->decimal('amount_paid', 18, 2)->change();
        });

        $this->roundColumns('loan_payments', ['amount', 'interest_payment', 'principal_payment', 'unpaid_interest', 'surcharge_payment']);
        Schema::table('loan_payments', function (Blueprint $table) {
            $table->decimal('amount', 18, 2)->change();
            $table->decimal('interest_payment', 18, 2)->change();
            $table->decimal('principal_payment', 18, 2)->change();
            $table->decimal('unpaid_interest', 18, 2)->change();
            $table->decimal('surcharge_payment', 18, 2)->change();
        });

        $this->roundColumns('loan_types', ['minimum_cbu', 'max_amount']);
        Schema::table('loan_types', function (Blueprint $table) {
            $table->decimal('minimum_cbu', 18, 2)->change();
            $table->decimal('max_amount', 18, 2)->change();
        });

        $this->roundColumns('loans', ['gross_amount', 'interest', 'service_fee', 'cbu_amount', 'imprest_amount', 'insurance_amount', 'loan_buyout', 'deductions_amount', 'monthly_payment', 'outstanding_balance']);
        Schema::table('loans', function (Blueprint $table) {
            $table->decimal('gross_amount', 18, 2)->change();
            $table->dropColumn('net_amount');
            $table->decimal('interest', 18, 2)->change();
            $table->decimal('service_fee', 18, 2)->change();
            $table->decimal('cbu_amount', 18, 2)->change();
            $table->decimal('imprest_amount', 18, 2)->change();
            $table->decimal('insurance_amount', 18, 2)->change();
            $table->decimal('loan_buyout', 18, 2)->change();
            $table->decimal('deductions_amount', 18, 2)->change();
            $table->decimal('monthly_payment', 16, 2)->change();
            $table->decimal('outstanding_balance', 18, 2)->change();
        });
        Schema::table('loans', function (Blueprint $table) {
            $table->decimal('net_amount', 18, 2)->virtualAs('gross_amount - deductions_amount');
        });

        $this->roundColumns('love_gifts', ['amount', 'interest', 'balance']);
        Schema::table('love_gifts', function (Blueprint $table) {
            $table->decimal('amount', 18, 2)->change();
            $table->dropColumn(['deposit', 'withdrawal']);
            $table->decimal('interest', 18, 2)->change();
            $table->decimal('balance', 18, 2)->change();
        });
        Schema::table('love_gifts', function (Blueprint $table) {
            $table->decimal('deposit', 18, 2)->virtualAs('if(amount >= 0, amount, null)');
            $table->decimal('withdrawal', 18, 2)->virtualAs('if(amount < 0, amount * -1, null)');
        });

        $this->roundColumns('member_types', ['default_amount_subscribed', 'minimum_initial_payment']);
        Schema::table('member_types', function (Blueprint $table) {
            $table->decimal('default_amount_subscribed', 18, 2)->change();
            $table->decimal('minimum_initial_payment', 18, 2)->change();
        });

        Schema::table('members', function (Blueprint $table) {
            $table->decimal('annual_income', 18, 2)->nullable()->unsigned()->change();
        });

        $this->roundColumns('mso_billing_payments', ['amount_due', 'amount_paid']);
        Schema::table('mso_billing_payments', function (Blueprint $table) {
            $table->decimal('amount_due', 18, 2)->change();
            $table->decimal('amount_paid', 18, 2)->change();
        });

        Schema::table('mso_subscriptions', function (Blueprint $table) {
            $table->decimal('amount', 18, 2)->change();
        });

        $this->roundColumns('revolving_funds', ['deposit', 'withdrawal']);
        Schema::table('revolving_funds', function (Blueprint $table) {
            $table->decimal('deposit', 18, 2)->nullable()->change();
            $table->decimal('withdrawal', 18, 2)->nullable()->change();
        });

        $this->roundColumns('savings', ['amount', 'interest', 'balance']);
        Schema::table('savings', function (Blueprint $table) {
            $table->decimal('amount', 18, 2)->change();
            $table->dropColumn(['deposit', 'withdrawal']);
            $table->decimal('interest', 18, 2)->change();
            $table->decimal('balance', 18, 2)->change();
        });
        Schema::table('savings', function (Blueprint $table) {
            $table->decimal('deposit', 18, 2)->virtualAs('if(amount >= 0, amount, null)');
            $table->decimal('withdrawal', 18, 2)->virtualAs('if(amount < 0, amount * -1, null)');
        });

        $this->roundColumns('time_deposits', ['amount', 'maturity_amount']);
        Schema::table('time_deposits', function (Blueprint $table) {
            $table->decimal('amount', 18, 2)->change();
            $table->decimal('maturity_amount', 18, 2)->change();
            $table->dropColumn('interest');
        });
        Schema::table('time_deposits', function (Blueprint $table) {
            $table->decimal('interest', 18, 2)->virtualAs('maturity_amount - amount');
        });

        $this->roundColumns('transactions', ['credit', 'debit']);
        Schema::table('transactions', function (Blueprint $table) {
            $table->decimal('credit', 18, 2)->nullable()->change();
            $table->decimal('debit', 18, 2)->nullable()->change();
        });
    }
};
