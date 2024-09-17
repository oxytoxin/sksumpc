<?php

use App\Models\User;
use App\Models\VoucherType;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('journal_entry_vouchers', function (Blueprint $table) {
            $table->id();
            $table->foreignIdFor(VoucherType::class)->nullable()->constrained();
            $table->string('name');
            $table->string('address')->nullable();
            $table->string('reference_number');
            $table->string('voucher_number');
            $table->text('description');
            $table->date('transaction_date')->default(DB::raw('(CURRENT_DATE)'));
            $table->foreignIdFor(User::class, 'bookkeeper_id');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('journal_entry_vouchers');
    }
};
