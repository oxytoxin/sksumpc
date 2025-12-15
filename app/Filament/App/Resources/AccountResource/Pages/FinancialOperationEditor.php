<?php

namespace App\Filament\App\Resources\AccountResource\Pages;

use Filament\Schemas\Schema;
use App\Filament\App\Resources\AccountResource;
use App\Models\Account;
use Filament\Forms\Components\Repeater;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Resources\Pages\Page;

class FinancialOperationEditor extends Page
{
    protected static string $resource = AccountResource::class;

    protected string $view = 'filament.app.resources.account-resource.pages.financial-operation-editor';

    public function form(Schema $schema): Schema
    {
        return $schema
            ->components([
                Repeater::make('items')
                    ->schema([
                        TextInput::make('name'),
                        Select::make('sum_of_accounts')
                            ->options(Account::whereNull('member_id')->pluck('name', 'id'))
                            ->multiple()
                            ->searchable()
                            ->preload(),
                    ]),
            ]);
    }
}
