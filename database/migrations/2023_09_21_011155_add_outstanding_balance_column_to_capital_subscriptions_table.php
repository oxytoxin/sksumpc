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
        Schema::table('capital_subscriptions', function (Blueprint $table) {
            $table->decimal('outstanding_balance', 18, 4)->after('par_value');
            $table->decimal('total_amount_paid', 18, 4)->virtualAs(DB::raw('IF(outstanding_balance > 0, amount_subscribed - outstanding_balance, amount_subscribed)'))->after('par_value');
            $table->decimal('actual_amount_paid', 18, 4)->default(0)->after('par_value');
        });
        DB::unprepared('
            CREATE TRIGGER update_cbu_outstanding_balance_on_payment_insert
            AFTER INSERT ON capital_subscription_payments
            FOR EACH ROW
            BEGIN
                UPDATE capital_subscriptions
                SET outstanding_balance = (SELECT amount_subscribed FROM (SELECT amount_subscribed FROM capital_subscriptions WHERE id = NEW.capital_subscription_id) as a) - (SELECT COALESCE(SUM(amount),0) FROM capital_subscription_payments WHERE capital_subscription_id = NEW.capital_subscription_id)
                , actual_amount_paid = (SELECT COALESCE(SUM(amount),0) FROM capital_subscription_payments WHERE capital_subscription_id = NEW.capital_subscription_id)
                WHERE id = NEW.capital_subscription_id;
            END;

            CREATE TRIGGER update_cbu_outstanding_balance_on_payment_delete
            AFTER DELETE ON capital_subscription_payments
            FOR EACH ROW
            BEGIN
                UPDATE capital_subscriptions
                SET outstanding_balance = (SELECT amount_subscribed FROM (SELECT amount_subscribed FROM capital_subscriptions WHERE id = OLD.capital_subscription_id) as a) - (SELECT COALESCE(SUM(amount),0) FROM capital_subscription_payments WHERE capital_subscription_id = OLD.capital_subscription_id)
                , actual_amount_paid = (SELECT COALESCE(SUM(amount),0) FROM capital_subscription_payments WHERE capital_subscription_id = OLD.capital_subscription_id)
                WHERE id = OLD.capital_subscription_id;
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
