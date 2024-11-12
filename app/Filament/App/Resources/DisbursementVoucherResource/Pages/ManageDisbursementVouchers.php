<?php

namespace App\Filament\App\Resources\DisbursementVoucherResource\Pages;

use App\Actions\Transactions\CreateTransaction;
use App\Filament\App\Resources\DisbursementVoucherResource;
use App\Models\DisbursementVoucher;
use App\Models\TransactionType;
use App\Oxytoxin\DTO\Transactions\TransactionData;
use DB;
use Filament\Actions;
use Filament\Resources\Pages\ManageRecords;

class ManageDisbursementVouchers extends ManageRecords
{
    protected static string $resource = DisbursementVoucherResource::class;

    public function mount(): void
    {
        parent::mount();
        data_set($this, 'tableFilters.transaction_date.transaction_date', (config('app.transaction_date')?->format('m/d/Y') ?? today()->format('m/d/Y')) . ' - ' . (config('app.transaction_date')?->format('m/d/Y') ?? today()->format('m/d/Y')));
    }

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make()
                ->action(function ($data) {
                    DB::beginTransaction();
                    $transactionType = TransactionType::firstWhere('name', 'CDJ');
                    $data['voucher_type_id'] = 6;
                    $items = $data['disbursement_voucher_items'];
                    unset($data['disbursement_voucher_items']);
                    $data['transaction_date'] = config('app.transaction_date') ?? today();
                    $dv = DisbursementVoucher::create($data);
                    foreach ($items as $item) {
                        unset($item['member_id']);
                        $dv->disbursement_voucher_items()->create($item);
                    }
                    DB::commit();
                })->createAnother(false),
        ];
    }
}
