<?php

namespace App\Filament\App\Resources\JournalEntryVoucherResource\Pages;

use App\Actions\Transactions\CreateTransaction;
use App\Filament\App\Resources\JournalEntryVoucherResource;
use App\Models\JournalEntryVoucher;
use App\Models\TransactionType;
use App\Oxytoxin\DTO\Transactions\TransactionData;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ManageRecords;
use Illuminate\Support\Facades\DB;

class ManageJournalEntryVouchers extends ManageRecords
{
    protected static string $resource = JournalEntryVoucherResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make()
                ->action(function ($data) {
                    DB::beginTransaction();
                    $transactionType = TransactionType::firstWhere('name', 'CDJ');
                    $items = $data['journal_entry_voucher_items'];
                    unset($data['journal_entry_voucher_items'], $data['member_id']);
                    $data['transaction_date'] = config('app.transaction_date') ?? today();
                    $jev = JournalEntryVoucher::create($data);
                    foreach ($items as $item) {
                        unset($item['member_id']);
                        $jev->journal_entry_voucher_items()->create($item);
                    }
                    DB::commit();
                })
                ->createAnother(false),
        ];
    }
}
