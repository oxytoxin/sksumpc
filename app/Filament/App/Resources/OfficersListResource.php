<?php

namespace App\Filament\App\Resources;

use App\Filament\App\Resources\OfficersListResource\Pages;
use App\Models\Member;
use App\Models\OfficersList;
use App\Models\Position;
use Awcodes\FilamentTableRepeater\Components\TableRepeater;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;

class OfficersListResource extends Resource
{
    protected static ?string $model = OfficersList::class;

    protected static ?string $navigationGroup = 'Share Capital';

    protected static ?int $navigationSort = 20;

    public static function shouldRegisterNavigation(): bool
    {
        return auth()->user()->can('manage cbu');
    }

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                TextInput::make('year'),
                TableRepeater::make('officers')
                    ->schema([
                        Select::make('member_id')->options(Member::pluck('full_name', 'id'))->searchable()->label('Name')->required(),
                        Select::make('position_id')->options(Position::pluck('name', 'id'))->searchable()->label('Position')->required(),
                    ])->hideLabels(),
            ])
            ->columns(1);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('year'),
            ])
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
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
            'index' => Pages\ListOfficersLists::route('/'),
            'create' => Pages\CreateOfficersList::route('/create'),
            'edit' => Pages\EditOfficersList::route('/{record}/edit'),
        ];
    }
}
