<?php

namespace App\Filament\App\Resources;

use Filament\Schemas\Schema;
use Filament\Actions\EditAction;
use Filament\Actions\DeleteAction;
use Filament\Actions\BulkActionGroup;
use App\Filament\App\Resources\DisapprovalReasonResource\Pages\ManageDisapprovalReasons;
use App\Filament\App\Resources\DisapprovalReasonResource\Pages;
use App\Models\DisapprovalReason;
use Filament\Forms\Components\TextInput;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class DisapprovalReasonResource extends Resource
{
    protected static ?string $model = DisapprovalReason::class;

    protected static string | \UnitEnum | null $navigationGroup = 'Management';

    protected static ?int $navigationSort = 16;

    public static function shouldRegisterNavigation(): bool
    {
        return auth()->user()->can('manage all');
    }

    public static function form(Schema $schema): Schema
    {
        return $schema
            ->components([
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
            ->recordActions([
                EditAction::make(),
                DeleteAction::make(),
            ])
            ->toolbarActions([
                BulkActionGroup::make([]),
            ]);
    }

    public static function getPages(): array
    {
        return [
            'index' => ManageDisapprovalReasons::route('/'),
        ];
    }
}
