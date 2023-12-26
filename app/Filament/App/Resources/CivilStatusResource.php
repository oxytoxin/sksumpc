<?php

namespace App\Filament\App\Resources;

use App\Filament\App\Resources\CivilStatusResource\Pages;
use App\Models\CivilStatus;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class CivilStatusResource extends Resource
{
    protected static ?string $model = CivilStatus::class;

    protected static ?string $navigationGroup = 'Management';

    protected static ?int $navigationSort = 18;

    public static function shouldRegisterNavigation(): bool
    {
        return auth()->user()->hasRole('manager');
    }

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                TextInput::make('name'),
            ]);
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
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ])
            ->emptyStateActions([
                Tables\Actions\CreateAction::make(),
            ]);
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ManageCivilStatuses::route('/'),
        ];
    }
}
