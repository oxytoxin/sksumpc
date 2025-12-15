<?php

    namespace App\Filament\App\Resources;

    use Filament\Forms\Components\Repeater;
    use Filament\Schemas\Schema;
    use Filament\Actions\EditAction;
    use Filament\Actions\DeleteAction;
    use Filament\Actions\BulkActionGroup;
    use Filament\Actions\DeleteBulkAction;
    use Filament\Actions\CreateAction;
    use App\Filament\App\Resources\SystemConfigurationResource\Pages\ManageSystemConfigurations;
    use App\Filament\App\Resources\SystemConfigurationResource\Pages;
    use App\Models\SystemConfiguration;
    use Filament\Forms\Components\TextInput;
    use Filament\Resources\Resource;
    use Filament\Tables;
    use Filament\Tables\Columns\TextColumn;
    use Filament\Tables\Table;

    class SystemConfigurationResource extends Resource
    {
        protected static ?string $model = SystemConfiguration::class;

        protected static string|\BackedEnum|null $navigationIcon = 'heroicon-o-cog-6-tooth';

        protected static ?int $navigationSort = 15;

        public static function shouldRegisterNavigation(): bool
        {
            return false;
        }

        public static function form(Schema $schema): Schema
        {
            return $schema
                ->components([
                    TextInput::make('name'),
                    Repeater::make('content')
                        ->schema([
                            TextInput::make('name'),
                            TextInput::make('value'),
                        ])->table([
                            Repeater\TableColumn::make('Name'),
                            Repeater\TableColumn::make('Value'),
                        ]),
                ])->columns(1);
        }

        public static function table(Table $table): Table
        {
            return $table
                ->columns([
                    TextColumn::make('name'),
                ])
                ->filters([
                    //
                ])
                ->recordActions([
                    EditAction::make(),
                    DeleteAction::make(),
                ])
                ->toolbarActions([
                    BulkActionGroup::make([
                        DeleteBulkAction::make(),
                    ]),
                ])
                ->emptyStateActions([
                    CreateAction::make(),
                ]);
        }

        public static function getPages(): array
        {
            return [
                'index' => ManageSystemConfigurations::route('/'),
            ];
        }
    }
