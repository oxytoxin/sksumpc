<?php

namespace App\Actions\MSO;

use App\Actions\Transactions\CreateTransaction;
use App\Enums\MsoTransactionTag;
use App\Enums\MsoType;
use App\Models\Imprest;
use App\Models\ImprestAccount;
use App\Models\LoveGift;
use App\Models\LoveGiftAccount;
use App\Models\Member;
use App\Models\Saving;
use App\Models\SavingsAccount;
use App\Oxytoxin\DTO\Transactions\TransactionData;
use App\Oxytoxin\Providers\ImprestsProvider;
use App\Oxytoxin\Providers\LoveGiftProvider;
use App\Oxytoxin\Providers\SavingsProvider;
use Filament\Notifications\Notification;
use Illuminate\Validation\ValidationException;

class WithdrawFromMsoAccount
{
    public function handle(MsoType $msoType, TransactionData $data)
    {
        $member = Member::find($data->member_id);
        $account = match ($msoType) {
            MsoType::SAVINGS => SavingsAccount::find($data->account_id),
            MsoType::IMPREST => ImprestAccount::find($data->account_id),
            MsoType::LOVE_GIFT => LoveGiftAccount::find($data->account_id),
        };
        $balance = match ($msoType) {
            MsoType::SAVINGS => $account->savings()->sum('amount'),
            MsoType::IMPREST => $member->imprests()->sum('amount'),
            MsoType::LOVE_GIFT => $member->love_gifts()->sum('amount'),
        };
        if ($balance - $data->debit < 0) {
            Notification::make()->title('Invalid Amount')->body('Amount exceeds account balance.')->danger()->send();
            throw ValidationException::withMessages([
                'mountedTableActionsData.0.amount' => 'Invalid Amount. Amount exceeds account balance.',
            ]);
        }
        // if ($balance - $data->debit < 500) {
        //     Notification::make()->title('Invalid Amount')->body('A P500 balance should remain.')->danger()->send();
        //     throw ValidationException::withMessages([
        //         'mountedTableActionsData.0.amount' => 'Invalid Amount. A P500 balance should remain.',
        //     ]);
        // }

        switch ($msoType) {
            case MsoType::SAVINGS:
                $data->tag = MsoTransactionTag::MEMBER_SAVINGS_WITHDRAWAL->value;
                $data->remarks ??= 'Member Withdrawal from Savings';

                $record = $this->withdrawToSavingsAccount($data);
                break;
            case MsoType::IMPREST:
                $data->tag = MsoTransactionTag::MEMBER_IMPREST_WITHDRAWAL->value;
                $data->remarks ??= 'Member Withdrawal from Imprest';

                $record = $this->withdrawToImprestAccount($data);
                break;
            case MsoType::LOVE_GIFT:
                $data->tag = MsoTransactionTag::MEMBER_LOVE_GIFT_WITHDRAWAL->value;
                $data->remarks ??= 'Member Withdrawal from Love Gifts';

                $record = $this->withdrawToLoveGiftsAccount($data);
                break;
        }

        $data->reference_number = $record->reference_number;
        app(CreateTransaction::class)->handle($data);

        return $record;
    }

    private function withdrawToSavingsAccount(TransactionData $data)
    {
        $savings = Saving::create([
            'savings_account_id' => $data->account_id,
            'payment_type_id' => $data->payment_type_id,
            'reference_number' => $data->reference_number,
            'amount' => $data->debit * -1,
            'interest_rate' => SavingsProvider::INTEREST_RATE,
            'member_id' => $data->member_id,
            'transaction_date' => $data->transaction_date,
        ]);

        return $savings;
    }

    private function withdrawToImprestAccount(TransactionData $data)
    {
        $imprest = Imprest::create([
            'payment_type_id' => $data->payment_type_id,
            'reference_number' => $data->reference_number,
            'amount' => $data->debit * -1,
            'interest_rate' => ImprestsProvider::INTEREST_RATE,
            'member_id' => $data->member_id,
            'transaction_date' => $data->transaction_date,
        ]);

        return $imprest;
    }

    private function withdrawToLoveGiftsAccount(TransactionData $data)
    {
        $love_gift = LoveGift::create([
            'payment_type_id' => $data->payment_type_id,
            'reference_number' => $data->reference_number,
            'amount' => $data->debit * -1,
            'interest_rate' => LoveGiftProvider::INTEREST_RATE,
            'member_id' => $data->member_id,
            'transaction_date' => $data->transaction_date,
        ]);

        return $love_gift;
    }
}
