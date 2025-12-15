<?php

namespace App\Filament\App\Resources\BalanceForwardedSummaryResource\RelationManagers;

use Filament\Schemas\Schema;
use Filament\Tables\Columns\TextColumn;
use Filament\Actions\CreateAction;
use Filament\Actions\EditAction;
use Filament\Actions\DeleteAction;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use App\Models\Account;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables;
use Filament\Tables\Enums\FiltersLayout;
use Filament\Tables\Filters\Filter;
use Filament\Tables\Table;

class BalanceForwardedEntriesRelationManager extends RelationManager
{
    protected static string $relationship = 'balance_forwarded_entries';

    public function form(Schema $schema): Schema
    {
        return $schema
            ->components([
                Select::make('account_id')
                    ->label('Account')
                    ->columnSpanFull()
                    ->searchable()
                    ->preload()
                    ->options(Account::withCode()->whereNull('member_id')->pluck('code', 'id')),
                TextInput::make('debit')
                    ->prefix('P')
                    ->live(true)
                    ->numeric(),
                TextInput::make('credit')
                    ->prefix('P')
                    ->live(true)
                    ->numeric(),
            ]);
    }

    public function table(Table $table): Table
    {
        return $table
            ->modifyQueryUsing(fn ($query) => $query->join('accounts', 'balance_forwarded_entries.account_id', 'accounts.id')->select(['accounts.*', 'balance_forwarded_entries.*', 'accounts.id as account_id']))
            ->columns([
                TextColumn::make('account.parent.name')->label('Parent Account'),
                TextColumn::make('account.name')->label('Account Name'),
                TextColumn::make('account.number')->label('Account Number'),
                TextColumn::make('debit')->numeric(2),
                TextColumn::make('credit')->numeric(2),
            ])
            ->filters([
                Filter::make('number')
                    ->schema([
                        Select::make('parent_account')
                            ->searchable()
                            ->preload()
                            ->options(Account::whereNull('member_id')->has('children')->pluck('name', 'id')),
                        TextInput::make('account_name'),
                        TextInput::make('account_number'),
                    ])
                    ->columns(3)
                    ->columnSpanFull()
                    ->query(function ($query, $state) {
                        $query
                            ->when($state['account_number'], fn ($q, $v) => $q->where('number', 'like', "%{$v}%"))
                            ->when($state['parent_account'], fn ($q, $v) => $q->where('parent_id', $v))
                            ->when($state['account_name'], fn ($q, $v) => $q->where('name', 'like', "%{$v}%"));
                    }),
            ])
            ->filtersLayout(FiltersLayout::AboveContent)
            ->headerActions([
                CreateAction::make(),
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
}
