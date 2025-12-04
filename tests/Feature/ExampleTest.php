<?php

use App\Actions\MSO\DepositToMsoAccount;
use App\Actions\MSO\WithdrawFromMsoAccount;
use App\Enums\MsoType;
use App\Models\TransactionType;
use App\Oxytoxin\DTO\Transactions\TransactionData;
use Illuminate\Foundation\Testing\DatabaseTransactions;

use function Pest\Laravel\assertDatabaseHas;

// uses(DatabaseTransactions::class);

it('can deposit to imprest', function () {
    $imprest = app(DepositToMsoAccount::class)->handle(MsoType::IMPREST, new TransactionData(
        1376,
        TransactionType::CRJ(),
        '123456',
        1,
        null,
        10000,
        357,
        null,
        null,
        '01/06/2025',
        null,
    ));
    assertDatabaseHas('imprests', [
        'id' => $imprest->id,
    ]);
});
it('can withdraw from imprest', function () {
    $imprest = app(WithdrawFromMsoAccount::class)->handle(MsoType::IMPREST, new TransactionData(
        1376,
        TransactionType::CRJ(),
        '123456',
        1,
        200,
        null,
        357,
        null,
        null,
        '01/06/2025',
        null,
    ));
    $imprest->revolving_fund()->create([
        'reference_number' => $imprest->reference_number,
        'withdrawal' => abs($imprest->amount),
        'transaction_date' => $imprest->transaction_date,
        'cashier_id' => 1,
    ]);
    assertDatabaseHas('imprests', [
        'id' => $imprest->id,
    ]);
});
