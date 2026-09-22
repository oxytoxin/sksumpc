<?php

namespace App\Filament\App\Resources\DisbursementVoucherResource\Pages;

use App\Actions\Memberships\BuildAccountClosureDisclosure;
use App\Filament\App\Resources\DisbursementVoucherResource;
use App\Models\DisbursementVoucher;
use App\Models\Member;
use DB;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ManageRecords;
use Illuminate\Validation\ValidationException;

class ManageDisbursementVouchers extends ManageRecords
{
    protected static string $resource = DisbursementVoucherResource::class;

    public function mount(): void
    {
        parent::mount();
        data_set($this, 'tableFilters.transaction_date.transaction_date', (config('app.transaction_date')?->format('m/d/Y') ?? today()->format('m/d/Y')).' - '.(config('app.transaction_date')?->format('m/d/Y') ?? today()->format('m/d/Y')));
    }

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make()
                ->action(function ($data) {
                    $data['voucher_type_id'] = 6;
                    $items = $this->resolveVoucherItems($data);
                    DB::beginTransaction();
                    unset(
                        $data['disbursement_voucher_items'],
                        $data['compute_net'],
                        $data['action'],
                        $data['closure_member_id'],
                        $data['closure_disclosure'],
                    );
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

    /**
     * @param  array<string, mixed>  $data
     * @return array<int, array<string, mixed>>
     */
    private function resolveVoucherItems(array $data): array
    {
        if (($data['action'] ?? null) === 'closed_account') {
            $member = Member::find($data['closure_member_id'] ?? null);

            if (! $member) {
                throw ValidationException::withMessages([
                    'closure_member_id' => 'Select a member account to close.',
                ]);
            }

            return app(BuildAccountClosureDisclosure::class)->handle($member)['voucher_items'];
        }

        return collect($data['disbursement_voucher_items'])
            ->map(function (array $item): array {
                unset($item['details']);

                return $item;
            })
            ->all();
    }
}
