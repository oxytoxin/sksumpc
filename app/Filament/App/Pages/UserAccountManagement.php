<?php

namespace App\Filament\App\Pages;

use App\Models\User;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Pages\Page;
use Filament\Tables\Actions\DeleteAction;
use Filament\Tables\Actions\EditAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Concerns\InteractsWithTable;
use Filament\Tables\Contracts\HasTable;
use Filament\Tables\Table;
use Hash;

class UserAccountManagement extends Page implements HasTable
{
    use InteractsWithTable;

    protected static ?string $navigationGroup = 'Management';

    protected static string $view = 'filament.app.pages.user-account-management';

    protected static ?int $navigationSort = 12;

    public static function shouldRegisterNavigation(): bool
    {
        return auth()->user()->hasRole('manager');
    }

    public function table(Table $table): Table
    {
        return $table
            ->query(User::query())
            ->columns([
                TextColumn::make('name'),
                TextColumn::make('roles.name')
            ])
            ->actions([
                EditAction::make()
                    ->form([
                        TextInput::make('name')
                            ->required(),
                        TextInput::make('email')
                            ->email()
                            ->required(),
                        TextInput::make('password')
                            ->password(),
                        Select::make('roles')
                            ->relationship('roles', 'name')
                    ])
                    ->action(function ($data, $record) {
                        $roles = $data['roles'];
                        unset($data['roles']);
                        if (!$data['password'])
                            unset($data['password']);
                        else
                            $data['password'] = Hash::make($data['password']);
                        $record->update($data);
                        $record->roles()->sync($roles);
                    }),
                DeleteAction::make()
            ]);
    }
}
