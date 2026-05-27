<?php

    namespace App\Filament\App\Resources\OrganizationResource\Pages;

    use Filament\Actions\DeleteAction;
    use App\Filament\App\Resources\OrganizationResource;
    use Filament\Actions;
    use Filament\Actions\EditAction;
    use Filament\Forms\Components\Select;
    use Filament\Resources\Pages\EditRecord;
    use SebastianBergmann\CodeCoverage\Driver\Selector;

    class EditOrganization extends EditRecord
    {
        protected static string $resource = OrganizationResource::class;

        protected function getHeaderActions(): array
        {
            return [
                DeleteAction::make(),
                Actions\Action::make('add')
                    ->label('Add Members')
                    ->closeModalByClickingAway(false)
                    ->schema([
                        Select::make('member_ids')
                            ->label('Members')
                            ->options(fn() => \App\Models\Member::pluck('full_name', 'id'))
                            ->multiple()
                    ])
                    ->action(function ($record, $data) {
                        $record->member_ids = collect($record->member_ids)->merge($data['member_ids'])->all();
                        $record->save();
                    }),
                Actions\Action::make('remove')
                    ->label('Remove Members')
                    ->closeModalByClickingAway(false)
                    ->schema([
                        Select::make('member_ids')
                            ->label('Members')
                            ->options(fn() => \App\Models\Member::pluck('full_name', 'id'))
                            ->multiple()
                    ])->action(function ($record, $data) {
                        $record->member_ids = collect($record->member_ids)->diff($data['member_ids'])->all();
                        $record->save();
                    }),
            ];
        }
    }
