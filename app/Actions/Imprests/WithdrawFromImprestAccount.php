<?php

namespace App\Actions\Imprests;

use App\Models\Imprest;
use App\Models\Member;
use App\Models\TransactionType;
use App\Oxytoxin\DTO\MSO\ImprestData;
use App\Oxytoxin\Providers\ImprestsProvider;
use Filament\Notifications\Notification;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;
use Lorisleiva\Actions\Concerns\AsAction;

class WithdrawFromImprestAccount
{
    use AsAction;

    public function handle(Member $member, ImprestData $data, TransactionType $transactionType)
    {
        if ($member->imprests()->sum('amount') - $data->amount < 500) {
            Notification::make()->title('Invalid Amount')->body('A P500 balance should remain.')->danger()->send();
            throw ValidationException::withMessages([
                'mountedTableActionsData.0.amount' => 'Invalid Amount. A P500 balance should remain.',
            ]);
        }
        DB::beginTransaction();
        $imprest_account = $member->imprest_account;
        $imprest = Imprest::create([
            'payment_type_id' => $data->payment_type_id,
            'reference_number' => $data->reference_number,
            'amount' => $data->amount * -1,
            'interest_rate' => ImprestsProvider::INTEREST_RATE,
            'member_id' => $member->id,
            'transaction_date' => $data->transaction_date,
        ]);
        $imprest_account->transactions()->create([
            'transaction_type_id' => $transactionType->id,
            'reference_number' => $imprest->reference_number,
            'debit' => $imprest->amount,
        ]);
        DB::commit();

        return $imprest;
    }
}
