<?php

    namespace App\Filament\App\Resources;

    use Filament\Forms\Components\Repeater;
    use Filament\Schemas\Schema;
    use Filament\Tables\Columns\TextColumn;
    use Filament\Actions\EditAction;
    use Filament\Actions\DeleteAction;
    use Filament\Actions\BulkActionGroup;
    use Filament\Actions\DeleteBulkAction;
    use App\Filament\App\Resources\OfficersListResource\Pages\ListOfficersLists;
    use App\Filament\App\Resources\OfficersListResource\Pages\CreateOfficersList;
    use App\Filament\App\Resources\OfficersListResource\Pages\EditOfficersList;
    use App\Filament\App\Resources\OfficersListResource\Pages;
    use App\Models\Member;
    use App\Models\OfficersList;
    use App\Models\Position;
    use Filament\Forms\Components\Select;
    use Filament\Forms\Components\TextInput;
    use Filament\Resources\Resource;
    use Filament\Tables;
    use Filament\Tables\Table;

    class OfficersListResource extends Resource
    {
        protected static ?string $model = OfficersList::class;

        protected static string|\UnitEnum|null $navigationGroup = 'Share Capital';

        protected static ?int $navigationSort = 20;

        public static function shouldRegisterNavigation(): bool
        {
            return auth()->user()->can('manage cbu');
        }

        public static function form(Schema $schema): Schema
        {
            return $schema
                ->components([
                    TextInput::make('year'),
                    Repeater::make('officers')
                        ->schema([
                            Select::make('member_id')->options(Member::pluck('full_name', 'id'))->searchable()->label('Name')->required(),
                            Select::make('position_id')->options(Position::pluck('name', 'id'))->searchable()->label('Position')->required(),
                        ])
                        ->table([
                            Repeater\TableColumn::make('Name'),
                            Repeater\TableColumn::make('Position'),
                        ]),
                ])
                ->columns(1);
        }

        public static function table(Table $table): Table
        {
            return $table
                ->columns([
                    TextColumn::make('year'),
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
                ]);
        }

        public static function getRelations(): array
        {
            return [];
        }

        public static function getPages(): array
        {
            return [
                'index' => ListOfficersLists::route('/'),
                'create' => CreateOfficersList::route('/create'),
                'edit' => EditOfficersList::route('/{record}/edit'),
            ];
        }
    }
