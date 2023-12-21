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
        });
        DB::unprepared('
            CREATE TRIGGER update_cbu_outstanding_balance
            BEFORE INSERT ON capital_subscription_payments
            FOR EACH ROW
            BEGIN
                UPDATE capital_subscriptions
                SET outstanding_balance = (SELECT outstanding_balance FROM capital_subscriptions WHERE id = NEW.capital_subscription_id) - NEW.amount
                WHERE id = NEW.capital_subscription_id;
            END
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
