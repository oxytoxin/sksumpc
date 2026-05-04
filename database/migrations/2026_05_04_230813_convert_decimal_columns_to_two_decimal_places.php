<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('balance_forwarded_entries', function (Blueprint $table) {
            $table->decimal('credit', 18, 2)->change();
            $table->decimal('debit', 18, 2)->change();
        });

        Schema::table('capital_subscription_billing_payments', function (Blueprint $table) {
            $table->decimal('amount_due', 18, 2)->change();
            $table->decimal('amount_paid', 18, 2)->change();
        });

        Schema::table('capital_subscription_payments', function (Blueprint $table) {
            $table->decimal('amount', 18, 2)->change();
            $table->decimal('deposit', 18, 2)->change();
            $table->decimal('withdrawal', 18, 2)->change();
            $table->decimal('running_balance', 18, 2)->change();
        });

        Schema::table('capital_subscriptions', function (Blueprint $table) {
            $table->decimal('number_of_shares', 18, 2)->change();
            $table->decimal('amount_subscribed', 18, 2)->change();
            $table->decimal('monthly_payment', 18, 2)->change();
            $table->decimal('initial_amount_paid', 18, 2)->change();
            $table->decimal('actual_amount_paid', 18, 2)->change();
            $table->decimal('total_amount_paid', 18, 2)->change();
            $table->decimal('outstanding_balance', 18, 2)->change();
        });

        Schema::table('cash_beginnings', function (Blueprint $table) {
            $table->decimal('amount', 18, 2)->change();
        });

        Schema::table('cash_collectible_billing_payments', function (Blueprint $table) {
            $table->decimal('amount_due', 18, 2)->change();
            $table->decimal('amount_paid', 18, 2)->change();
        });

        Schema::table('cash_collectible_payments', function (Blueprint $table) {
            $table->decimal('amount', 18, 2)->change();
        });

        Schema::table('cash_collectible_subscriptions', function (Blueprint $table) {
            $table->decimal('amount', 18, 2)->change();
            $table->decimal('billable_amount', 18, 2)->change();
        });

        Schema::table('disbursement_voucher_items', function (Blueprint $table) {
            $table->decimal('credit', 18, 2)->change();
            $table->decimal('debit', 18, 2)->change();
        });

        Schema::table('imprests', function (Blueprint $table) {
            $table->decimal('amount', 18, 2)->change();
            $table->decimal('deposit', 18, 2)->change();
            $table->decimal('withdrawal', 18, 2)->change();
            $table->decimal('interest', 18, 2)->change();
            $table->decimal('balance', 18, 2)->change();
        });

        Schema::table('journal_entry_voucher_items', function (Blueprint $table) {
            $table->decimal('credit', 18, 2)->change();
            $table->decimal('debit', 18, 2)->change();
        });

        Schema::table('loan_applications', function (Blueprint $table) {
            $table->decimal('desired_amount', 18, 2)->change();
            $table->decimal('cbu_amount', 18, 2)->change();
            $table->decimal('monthly_payment', 16, 2)->change();
        });

        Schema::table('loan_billing_payments', function (Blueprint $table) {
            $table->decimal('amount_due', 18, 2)->change();
            $table->decimal('amount_paid', 18, 2)->change();
        });

        Schema::table('loan_payments', function (Blueprint $table) {
            $table->decimal('amount', 18, 2)->change();
            $table->decimal('interest_payment', 18, 2)->change();
            $table->decimal('principal_payment', 18, 2)->change();
            $table->decimal('unpaid_interest', 18, 2)->change();
            $table->decimal('surcharge_payment', 18, 2)->change();
        });

        Schema::table('loan_types', function (Blueprint $table) {
            $table->decimal('minimum_cbu', 18, 2)->change();
            $table->decimal('max_amount', 18, 2)->change();
        });

        Schema::table('loans', function (Blueprint $table) {
            $table->decimal('gross_amount', 18, 2)->change();
            $table->decimal('net_amount', 18, 2)->change();
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

        Schema::table('love_gifts', function (Blueprint $table) {
            $table->decimal('amount', 18, 2)->change();
            $table->decimal('deposit', 18, 2)->change();
            $table->decimal('withdrawal', 18, 2)->change();
            $table->decimal('interest', 18, 2)->change();
            $table->decimal('balance', 18, 2)->change();
        });

        Schema::table('member_types', function (Blueprint $table) {
            $table->decimal('default_amount_subscribed', 18, 2)->change();
            $table->decimal('minimum_initial_payment', 18, 2)->change();
        });

        Schema::table('members', function (Blueprint $table) {
            $table->decimal('annual_income', 18, 2)->change();
        });

        Schema::table('mso_billing_payments', function (Blueprint $table) {
            $table->decimal('amount_due', 18, 2)->change();
            $table->decimal('amount_paid', 18, 2)->change();
        });

        Schema::table('mso_subscriptions', function (Blueprint $table) {
            $table->decimal('amount', 18, 2)->change();
        });

        Schema::table('revolving_funds', function (Blueprint $table) {
            $table->decimal('deposit', 18, 2)->change();
            $table->decimal('withdrawal', 18, 2)->change();
        });

        Schema::table('savings', function (Blueprint $table) {
            $table->decimal('amount', 18, 2)->change();
            $table->decimal('deposit', 18, 2)->change();
            $table->decimal('withdrawal', 18, 2)->change();
            $table->decimal('interest', 18, 2)->change();
            $table->decimal('balance', 18, 2)->change();
        });

        Schema::table('time_deposits', function (Blueprint $table) {
            $table->decimal('amount', 18, 2)->change();
            $table->decimal('maturity_amount', 18, 2)->change();
            $table->decimal('interest', 18, 2)->change();
        });

        Schema::table('transactions', function (Blueprint $table) {
            $table->decimal('credit', 18, 2)->change();
            $table->decimal('debit', 18, 2)->change();
        });
    }
};
