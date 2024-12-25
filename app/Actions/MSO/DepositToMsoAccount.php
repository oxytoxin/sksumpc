<?php

namespace App\Actions\MSO;

use App\Actions\Transactions\CreateTransaction;
use App\Enums\MsoTransactionTag;
use App\Enums\MsoType;
use App\Models\Imprest;
use App\Models\LoveGift;
use App\Models\Saving;
use App\Oxytoxin\DTO\Transactions\TransactionData;
use App\Oxytoxin\Providers\ImprestsProvider;
use App\Oxytoxin\Providers\LoveGiftProvider;
use App\Oxytoxin\Providers\SavingsProvider;

class DepositToMsoAccount
{
    public function handle(MsoType $msoType, TransactionData $data)
    {
        switch ($msoType) {
            case MsoType::SAVINGS:
                $data->tag = MsoTransactionTag::MEMBER_SAVINGS_DEPOSIT->value;
                $data->remarks ??= 'Member Deposit to Savings';

                $record = $this->depositToSavingsAccount($data);
                break;
            case MsoType::IMPREST:
                $data->tag = MsoTransactionTag::MEMBER_IMPREST_DEPOSIT->value;
                $data->remarks ??= 'Member Deposit to Imprest';

                $record = $this->depositToImprestAccount($data);
                break;
            case MsoType::LOVE_GIFT:
                $data->tag = MsoTransactionTag::MEMBER_LOVE_GIFT_DEPOSIT->value;
                $data->remarks ??= 'Member Deposit to Love Gifts';

                $record = $this->depositToLoveGiftsAccount($data);
                break;

        }

        app(CreateTransaction::class)->handle($data);

        return $record;
    }

    private function depositToSavingsAccount(TransactionData $data)
    {
        $savings = Saving::create([
            'savings_account_id' => $data->account_id,
            'payment_type_id' => $data->payment_type_id,
            'reference_number' => $data->reference_number,
            'amount' => $data->credit,
            'interest_rate' => SavingsProvider::INTEREST_RATE,
            'member_id' => $data->member_id,
            'transaction_date' => $data->transaction_date,
        ]);

        return $savings;
    }

    private function depositToImprestAccount(TransactionData $data)
    {
        $imprest = Imprest::create([
            'payment_type_id' => $data->payment_type_id,
            'reference_number' => $data->reference_number,
            'amount' => $data->credit,
            'interest_rate' => ImprestsProvider::INTEREST_RATE,
            'member_id' => $data->member_id,
            'transaction_date' => $data->transaction_date,
        ]);

        return $imprest;
    }

    private function depositToLoveGiftsAccount(TransactionData $data)
    {
        $love_gift = LoveGift::create([
            'payment_type_id' => $data->payment_type_id,
            'reference_number' => $data->reference_number,
            'amount' => $data->credit,
            'interest_rate' => LoveGiftProvider::INTEREST_RATE,
            'member_id' => $data->member_id,
            'transaction_date' => $data->transaction_date,
        ]);

        return $love_gift;
    }
}
