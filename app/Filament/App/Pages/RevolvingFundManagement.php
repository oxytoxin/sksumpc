<?php

namespace App\Filament\App\Pages;

use App\Models\RevolvingFund;
use Filament\Pages\Page;
use Filament\Tables\Table;
use Filament\Tables\Contracts\HasTable;
use App\Models\RevolvingFundReplenishment;
use Filament\Actions\Action;
use Filament\Forms\Components\TextInput;
use Filament\Notifications\Notification;
use Filament\Tables\Concerns\InteractsWithTable;

class RevolvingFundManagement extends Page implements HasTable
{
    use InteractsWithTable;

    protected static string $view = 'filament.app.pages.revolving-fund-management';

    protected static ?string $navigationGroup = 'Cashier';

    protected static ?int $navigationSort = 4;

    protected function getHeaderActions(): array
    {
        return [
            Action::make('cash_in')
                ->form([
                    TextInput::make('reference_number')->required(),
                    TextInput::make('amount')
                        ->moneymask()
                ])
                ->action(function ($data) {
                    RevolvingFund::create([
                        'reference_number' => $data['reference_number'],
                        'deposit' => $data['amount'],
                        'transaction_date' => config('app.transaction_date')
                    ]);
                    Notification::make()->title('Revolving fund replenished!')->success()->send();
                })
        ];
    }

    public function table(Table $table): Table
    {
        return $table
            ->query(
                RevolvingFund::query()
                    ->whereCashierId(auth()->id())
                    ->whereMonth('transaction_date', config('app.transaction_date')->month)
                    ->whereYear('transaction_date', config('app.transaction_date')->year)
            );
    }
}
