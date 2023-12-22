<?php

namespace App\Filament\App\Resources;

use App\Filament\App\Resources\OfficersListResource\Pages;
use App\Filament\App\Resources\OfficersListResource\RelationManagers;
use App\Models\OfficersList;
use Awcodes\FilamentTableRepeater\Components\TableRepeater;
use Filament\Forms;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class OfficersListResource extends Resource
{
    protected static ?string $model = OfficersList::class;

    protected static ?string $navigationIcon = 'icon-membership';

    protected static ?int $navigationSort = 6;

    public static function shouldRegisterNavigation(): bool
    {
        return auth()->user()->can('manage cbu');
    }

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                TextInput::make('year')->required(),
                TableRepeater::make('officers')
                    ->hideLabels()
                    ->schema([
                        TextInput::make('name')->required()->columnSpanFull(),
                        TextInput::make('position')
                            ->datalist([
                                'BOD Chairperson',
                                'BOD Vice-Chairperson',
                                'BOD Member',
                                'Election Committee',
                                'Audit Committee',
                                'Ethics Committee',
                                'Mediation Committee',
                                'Credit Committee',
                                'Education and Training Committee',
                                'Bids and Awards Committee',
                                'Gender and Development Committee',
                                'Treasurer',
                                'Secretary',
                                'Manager',
                                'Bookkeeper',
                                'Loan Officer',
                                'Teller',
                                'Clerk',
                                'Liaison Officer',
                                'Utility',
                            ])
                    ])
            ])->columns(1);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('year')
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

    public static function getPages(): array
    {
        return [
            'index' => Pages\ManageOfficersLists::route('/'),
        ];
    }
}
