<?php

namespace App\Filament\App\Pages;

use App\Actions\RevolvingFund\ReplenishRevolvingFund;
use App\Models\RevolvingFund;
use Filament\Actions\Action;
use Filament\Actions\DeleteAction;
use Filament\Forms\Components\TextInput;
use Filament\Notifications\Notification;
use Filament\Pages\Page;
use Filament\Tables\Concerns\InteractsWithTable;
use Filament\Tables\Contracts\HasTable;
use Filament\Tables\Table;

class RevolvingFundManagement extends Page implements HasTable
{
    use InteractsWithTable;

    protected static string $view = 'filament.app.pages.revolving-fund-management';

    protected static ?string $navigationGroup = 'Cashier';

    protected static ?int $navigationSort = 4;

    public static function shouldRegisterNavigation(): bool
    {
        return auth()->user()->can('manage payments');
    }

    protected function getHeaderActions(): array
    {
        return [
            Action::make('cash_in')
                ->form([
                    TextInput::make('reference_number')->required(),
                    TextInput::make('amount')
                        ->moneymask(),
                ])
                ->action(function ($data, ReplenishRevolvingFund $replenishRevolvingFund) {
                    $replenishRevolvingFund->handle($data['reference_number'], $data['amount']);
                    Notification::make()->title('Revolving fund replenished!')->success()->send();
                }),
            Action::make('clear_cash')
                ->requiresConfirmation()
                ->color('danger')
                ->action(function () {
                    RevolvingFund::query()->delete();
                    Notification::make()->title('Revolving fund cleared!')->success()->send();
                }),
        ];
    }

    public function table(Table $table): Table
    {
        return $table
            ->query(
                RevolvingFund::query()
                    ->whereDate('transaction_date', config('app.transaction_date'))
                // ->whereMonth('transaction_date', config('app.transaction_date')->month)
                // ->whereYear('transaction_date', config('app.transaction_date')->year)
            );
    }
}
