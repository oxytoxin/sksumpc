<?php

namespace App\Filament\App\Resources\BalanceForwardedSummaryResource\RelationManagers;

use Filament\Forms;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Form;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class BalanceForwardedEntriesRelationManager extends RelationManager
{
    protected static string $relationship = 'balance_forwarded_entries';

    public function form(Form $form): Form
    {
        return $form
            ->schema([
                Select::make('trial_balance_entry_id')
                    ->label('Trial Balance Entry')
                    ->columnSpanFull()
                    ->relationship('trial_balance_entry', 'codename', fn ($query) => $query->whereNotNull('code')),
                TextInput::make('debit')
                    ->moneymask(),
                TextInput::make('credit')
                    ->moneymask(),
            ]);
    }

    public function table(Table $table): Table
    {
        return $table
            ->recordTitleAttribute('trial_balance_entry_id')
            ->columns([
                Tables\Columns\TextColumn::make('trial_balance_entry.codename'),
                Tables\Columns\TextColumn::make('debit'),
                Tables\Columns\TextColumn::make('credit'),
            ])
            ->filters([
                //
            ])
            ->headerActions([
                Tables\Actions\CreateAction::make(),
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
}
