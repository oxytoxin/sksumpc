<?php

namespace App\Filament\App\Resources\MsoBillingResource\Pages;

use Filament\Actions\CreateAction;
use App\Filament\App\Resources\MsoBillingResource;
use App\Models\Member;
use App\Models\MsoBilling;
use App\Models\MsoBillingPayment;
use Auth;
use DB;
use Filament\Actions;
use Filament\Resources\Pages\ManageRecords;
use Livewire\Attributes\Computed;

class ManageMsoBillings extends ManageRecords
{
    protected static string $resource = MsoBillingResource::class;

    protected ?string $heading = 'MSO Billing';

    protected static ?string $title = 'MSO Billing';

    #[Computed]
    public function UserIsCashier()
    {
        return Auth::user()->can('manage payments');
    }

    #[Computed]
    public function UserIsCbuOfficer()
    {
        return Auth::user()->can('manage cbu');
    }

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make()
                ->label('New MSO Billing')
                ->action(function ($data) {
                    $amount = $data['amount'] ?? 0;
                    $members = Member::query()
                        ->when($data['member_type_id'] ?? null, fn ($query, $value) => $query->where('member_type_id', $value))
                        ->when($data['member_subtype_id'] ?? null, fn ($query, $value) => $query->where('member_subtype_id', $value));
                    unset($data['amount'], $data['member_type_id'], $data['member_subtype_id']);
                    DB::beginTransaction();
                    $mso_billing = MsoBilling::create($data);
                    if ($amount) {
                        if ($mso_billing->type == 1) {
                            $savings_accounts = $members->with('savings_accounts')
                                ->get()
                                ->pluck('savings_accounts')
                                ->flatten();
                            $savings_accounts->each(function ($savings_account) use ($amount, $mso_billing) {
                                MsoBillingPayment::create([
                                    'mso_billing_id' => $mso_billing->id,
                                    'account_id' => $savings_account->id,
                                    'member_id' => $savings_account->member_id,
                                    'payee' => $savings_account->name,
                                    'amount_due' => $amount,
                                    'amount_paid' => $amount,
                                ]);
                            });
                        }
                        if ($mso_billing->type == 2) {
                            $imprest_accounts = $members->with('imprest_account')
                                ->get()
                                ->pluck('imprest_account')
                                ->flatten();
                            $imprest_accounts->each(function ($imprest_account) use ($amount, $mso_billing) {
                                MsoBillingPayment::create([
                                    'mso_billing_id' => $mso_billing->id,
                                    'account_id' => $imprest_account->id,
                                    'member_id' => $imprest_account->member_id,
                                    'payee' => $imprest_account->name,
                                    'amount_due' => $amount,
                                    'amount_paid' => $amount,
                                ]);
                            });
                        }
                        if ($mso_billing->type == 3) {
                            $love_gift_accounts = $members->with('love_gift_account')
                                ->get()
                                ->pluck('love_gift_account')
                                ->flatten();
                            $love_gift_accounts->each(function ($love_gift_account) use ($amount, $mso_billing) {
                                MsoBillingPayment::create([
                                    'mso_billing_id' => $mso_billing->id,
                                    'account_id' => $love_gift_account->id,
                                    'member_id' => $love_gift_account->member_id,
                                    'payee' => $love_gift_account->name,
                                    'amount_due' => $amount,
                                    'amount_paid' => $amount,
                                ]);
                            });
                        }
                    }
                    DB::commit();
                }),
        ];
    }
}
