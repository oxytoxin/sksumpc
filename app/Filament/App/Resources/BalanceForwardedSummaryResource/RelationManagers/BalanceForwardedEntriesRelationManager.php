<?php

namespace App\Filament\App\Resources\BalanceForwardedSummaryResource\RelationManagers;

use App\Models\Account;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Form;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables;
use Filament\Tables\Table;

class BalanceForwardedEntriesRelationManager extends RelationManager
{
    protected static string $relationship = 'balance_forwarded_entries';

    public function form(Form $form): Form
    {
        return $form
            ->schema([
                Select::make('account_id')
                    ->label('Account')
                    ->columnSpanFull()
                    ->searchable()
                    ->preload()
                    ->options(Account::withCode()->whereDoesntHave('children', fn ($q) => $q->whereNull('member_id'))->whereNull('member_id')->pluck('code', 'id')),
                TextInput::make('debit')
                    ->moneymask(),
                TextInput::make('credit')
                    ->moneymask(),
            ]);
    }

    public function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('account.number')->label('Account Number'),
                Tables\Columns\TextColumn::make('account.fullname')->label('Account Name'),
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
