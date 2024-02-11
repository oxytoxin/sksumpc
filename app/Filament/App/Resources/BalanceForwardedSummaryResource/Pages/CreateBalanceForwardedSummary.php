<?php

namespace App\Filament\App\Resources\BalanceForwardedSummaryResource\Pages;

use App\Filament\App\Resources\BalanceForwardedSummaryResource;
use App\Models\BalanceForwardedSummary;
use Carbon\Carbon;
use Filament\Resources\Pages\CreateRecord;
use Illuminate\Database\Eloquent\Model;

class CreateBalanceForwardedSummary extends CreateRecord
{
    protected static string $resource = BalanceForwardedSummaryResource::class;

    protected function handleRecordCreation(array $data): Model
    {
        return BalanceForwardedSummary::create([
            'generated_date' => Carbon::create(month: $data['month'], year: $data['year'])->endOfMonth(),
        ]);
    }
}
