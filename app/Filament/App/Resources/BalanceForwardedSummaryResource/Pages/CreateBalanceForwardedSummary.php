<?php

namespace App\Filament\App\Resources\BalanceForwardedSummaryResource\Pages;

use App\Filament\App\Resources\BalanceForwardedSummaryResource;
use App\Models\Account;
use App\Models\BalanceForwardedEntry;
use App\Models\BalanceForwardedSummary;
use Carbon\Carbon;
use DB;
use Filament\Resources\Pages\CreateRecord;
use Illuminate\Database\Eloquent\Model;

class CreateBalanceForwardedSummary extends CreateRecord
{
    protected static string $resource = BalanceForwardedSummaryResource::class;

    protected function handleRecordCreation(array $data): Model
    {
        DB::beginTransaction();
        $summary = BalanceForwardedSummary::create([
            'generated_date' => Carbon::create(month: $data['month'], year: $data['year'])->endOfMonth(),
        ]);
        Account::whereDoesntHave('children', fn ($q) => $q->whereNull('member_id'))->whereNull('member_id')->each(function ($account) use ($summary) {
            BalanceForwardedEntry::create([
                'account_id' => $account->id,
                'balance_forwarded_summary_id' => $summary->id,
            ]);
        });
        DB::commit();

        return $summary;
    }
}
