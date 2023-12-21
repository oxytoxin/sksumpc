<?php

use App\Models\MemberType;
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
        Schema::create('member_types', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->decimal('default_number_of_shares', 8, 2)->default(0);
            $table->decimal('default_amount_subscribed', 18, 4)->default(0);
            $table->decimal('minimum_initial_payment', 18, 4)->default(0);
            $table->timestamps();
        });

        MemberType::create([
            'name' => 'REGULAR-PERMANENT',
            'minimum_initial_payment' => 6500,
            'default_amount_subscribed' => 25000,
            'default_number_of_shares' => 50,
        ]);
        MemberType::create([
            'name' => 'REGULAR-JO',
            'minimum_initial_payment' => 6500,
            'default_amount_subscribed' => 25000,
            'default_number_of_shares' => 50,
        ]);
        MemberType::create([
            'name' => 'ASSOCIATE',
            'minimum_initial_payment' => 2500,
            'default_amount_subscribed' => 10000,
            'default_number_of_shares' => 20,
        ]);
        MemberType::create([
            'name' => 'LABORATORY'
        ]);
        MemberType::create([
            'name' => 'RETIREE'
        ]);
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('member_types');
    }
};
