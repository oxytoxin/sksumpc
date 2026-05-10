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
                EditAction::make()
                    ->label('Edit Members')
                    ->fillForm(
                        fn($record) => ['member_ids' => $record->member_ids]
                    )
                    ->schema([
                        Select::make('member_ids')
                            ->label('Members')
                            ->options(fn() => \App\Models\Member::pluck('full_name', 'id'))
                            ->multiple()
                    ])
                    ->action(function ($record, $data) {
                        $record->member_ids = $data['member_ids'];
                        $record->save();
                        $this->refreshFormData([
                            'members',
                        ]);
                    })
            ];
        }
    }
