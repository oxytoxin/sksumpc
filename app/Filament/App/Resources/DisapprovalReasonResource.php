<?php

namespace App\Filament\App\Resources;

use App\Filament\App\Resources\DisapprovalReasonResource\Pages;
use App\Models\DisapprovalReason;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class DisapprovalReasonResource extends Resource
{
    protected static ?string $model = DisapprovalReason::class;

    protected static ?string $navigationGroup = 'Management';

    protected static ?int $navigationSort = 16;

    public static function shouldRegisterNavigation(): bool
    {
        return auth()->user()->can('manage all');
    }

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                TextInput::make('name')->required(),
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
                Tables\Actions\BulkActionGroup::make([]),
            ]);
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ManageDisapprovalReasons::route('/'),
        ];
    }
}
