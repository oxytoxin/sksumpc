<?php

    namespace App\Filament\App\Resources\BalanceForwardedSummaryResource\Pages;

    use Filament\Actions\DeleteAction;
    use App\Filament\App\Resources\BalanceForwardedSummaryResource;
    use Carbon\Carbon;
    use Filament\Actions;
    use Filament\Resources\Pages\EditRecord;
    use Illuminate\Database\Eloquent\Model;

    class EditBalanceForwardedSummary extends EditRecord
    {
        protected static string $resource = BalanceForwardedSummaryResource::class;

        protected function getHeaderActions(): array
        {
            return [
                DeleteAction::make(),
            ];
        }

        protected function fillForm(): void
        {
            $record = $this->getRecord();
            $data = $record->attributesToArray();

            $data['month'] = $record->generated_date?->month;
            $data['year'] = $record->generated_date?->year;

        }

        /**
         * @param  array<string, mixed>  $data
         */
        protected function handleRecordUpdate(Model $record, array $data): Model
        {
            $data['generated_date'] = Carbon::create(month: $data['month'], year: $data['year'])->endOfMonth();
            unset($data['month'], $data['year']);
            $record->update($data);

            return $record;
        }
    }
