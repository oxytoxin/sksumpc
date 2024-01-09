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
        Schema::create('love_gifts', function (Blueprint $table) {
            $table->id();
            $table->foreignId('member_id')->constrained();
            $table->foreignId('payment_type_id')->constrained();
            $table->string('reference_number');
            $table->decimal('amount', 18, 4);
            $table->decimal('deposit', 18, 4)->nullable()->virtualAs('IF(amount >= 0, amount, null)');
            $table->decimal('withdrawal', 18, 4)->nullable()->virtualAs('IF(amount < 0, amount * -1, null)');
            $table->decimal('interest_rate', 7, 4);
            $table->decimal('interest', 18, 4)->default(0);
            $table->date('transaction_date')->default(DB::raw('CURDATE()'));
            $table->date('interest_date')->nullable();
            $table->decimal('balance', 18, 4)->default(0);
            $table->boolean('accrued')->default(false);
            $table->foreignId('cashier_id')->nullable()->constrained('users', 'id')->nullOnDelete();
            $table->timestamps();
        });

        DB::unprepared('
        CREATE TRIGGER update_love_gifts_balance BEFORE INSERT ON love_gifts
        FOR EACH ROW
        BEGIN
            DECLARE total_balance DECIMAL(18, 4);

            SELECT COALESCE(SUM(amount), 0) INTO total_balance
            FROM love_gifts
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
        Schema::dropIfExists('love_gifts');
    }
};
