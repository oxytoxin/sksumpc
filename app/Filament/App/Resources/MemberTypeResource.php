<?php

namespace App\Filament\App\Resources;

use App\Filament\App\Resources\MemberTypeResource\Pages;
use App\Models\MemberType;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class MemberTypeResource extends Resource
{
    protected static ?string $model = MemberType::class;

    protected static ?string $navigationGroup = 'Management';

    protected static ?int $navigationSort = 20;

    public static function shouldRegisterNavigation(): bool
    {
        return auth()->user()->hasRole('manager') || auth()->user()->can('manage cbu');
    }

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                TextInput::make('name')
                    ->required()
                    ->maxLength(125),
                TextInput::make('default_number_of_shares')
                    ->required()
                    ->numeric(),
                TextInput::make('default_amount_subscribed')
                    ->required()
                    ->moneymask(),
                TextInput::make('minimum_initial_payment')
                    ->required()
                    ->moneymask(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('name')
                    ->searchable(),
                TextColumn::make('default_number_of_shares'),
                TextColumn::make('default_amount_subscribed')->money('PHP'),
                TextColumn::make('minimum_initial_payment')->money('PHP'),
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
            'index' => Pages\ManageMemberTypes::route('/'),
        ];
    }
}
