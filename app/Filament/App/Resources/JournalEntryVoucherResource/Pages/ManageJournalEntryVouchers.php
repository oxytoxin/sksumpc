<?php

namespace App\Filament\App\Resources\JournalEntryVoucherResource\Pages;

use App\Filament\App\Resources\JournalEntryVoucherResource;
use App\Models\JournalEntryVoucher;
use App\Models\TransactionType;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ManageRecords;
use Illuminate\Support\Facades\DB;

class ManageJournalEntryVouchers extends ManageRecords
{
    protected static string $resource = JournalEntryVoucherResource::class;

    public function mount(): void
    {
        parent::mount();
        data_set($this, 'tableFilters.transaction_date.transaction_date', (config('app.transaction_date')?->format('m/d/Y') ?? today()->format('m/d/Y')) . ' - ' . (config('app.transaction_date')?->format('m/d/Y') ?? today()->format('m/d/Y')));
    }

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make()
                ->action(function ($data) {
                    DB::beginTransaction();
                    $transactionType = TransactionType::CDJ();
                    $data['voucher_type_id'] = 6;
                    $items = $data['journal_entry_voucher_items'];
                    unset($data['journal_entry_voucher_items'], $data['compute_net'],);
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
