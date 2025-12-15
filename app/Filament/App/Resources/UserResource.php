<?php

namespace App\Filament\App\Resources;

use Filament\Schemas\Schema;
use Filament\Actions\EditAction;
use Filament\Actions\DeleteAction;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use App\Filament\App\Resources\UserResource\Pages\ManageUsers;
use App\Filament\App\Resources\UserResource\Pages;
use App\Models\User;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Enums\FiltersLayout;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;

class UserResource extends Resource
{
    protected static ?string $model = User::class;

    protected static string | \UnitEnum | null $navigationGroup = 'Bookkeeping';

    public static function shouldRegisterNavigation(): bool
    {
        return auth()->user()->can('manage bookkeeping');
    }

    public static function form(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('name')->required(),
                TextInput::make('email')->required()->unique(ignoreRecord: true),
                TextInput::make('password')->password()->dehydrated(fn ($state) => filled($state)),
                Select::make('roles')
                    ->relationship('roles', 'name')
                    ->preload()
                    ->multiple(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('name')->searchable(),
                TextColumn::make('email')->searchable(),
                TextColumn::make('roles.name'),
            ])
            ->filtersLayout(FiltersLayout::AboveContent)
            ->filters([
                SelectFilter::make('role')
                    ->options([
                        'staff' => 'Staff',
                        'member' => 'Member',
                    ])
                    ->default('staff')
                    ->query(
                        fn ($query, $state) => $query
                            ->when($state['value'] == 'member', fn ($q) => $q->whereRelation('roles', 'name', 'member'))
                            ->when($state['value'] == 'staff', fn ($q) => $q->whereRelation('roles', 'name', '!=', 'member'))
                    ),
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
        return [
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => ManageUsers::route('/'),
        ];
    }
}
