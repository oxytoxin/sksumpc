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

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make()
                ->action(function ($data) {
                    DB::beginTransaction();
                    $transactionType = TransactionType::firstWhere('name', 'CDJ');
                    $items = $data['disbursement_voucher_items'];
                    unset($data['disbursement_voucher_items'], $data['member_id']);
                    $dv = DisbursementVoucher::create($data);
                    foreach ($items as $item) {
                        app(CreateTransaction::class)->handle(new TransactionData(
                            member_id: $item['member_id'],
                            account_id: $item['account_id'],
                            transactionType: $transactionType,
                            reference_number: $dv->reference_number,
                            debit: $item['debit'],
                            credit: $item['credit'],
                        ));
                        unset($item['member_id']);
                        $dv->disbursement_voucher_items()->create($item);
                    }
                    DB::commit();
                })->createAnother(false),
        ];
    }
}
