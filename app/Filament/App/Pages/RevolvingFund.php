<?php

namespace App\Filament\App\Pages;

use App\Models\Saving;
use App\Models\Imprest;
use Filament\Pages\Page;
use Filament\Tables\Table;
use Filament\Tables\Contracts\HasTable;
use App\Models\RevolvingFundReplenishment;
use Filament\Actions\Action;
use Filament\Forms\Components\TextInput;
use Filament\Notifications\Notification;
use Filament\Tables\Concerns\InteractsWithTable;

class RevolvingFund extends Page implements HasTable
{
    use InteractsWithTable;

    protected static string $view = 'filament.app.pages.revolving-fund';

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
                    RevolvingFundReplenishment::create([
                        'reference_number' => $data['reference_number'],
                        'amount' => $data['amount'],
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
                function () {
                    $savings = Saving::query()
                        ->withoutGlobalScopes()
                        ->whereNotNull("withdrawal")
                        ->whereCashierId(auth()->id())
                        ->whereMonth('transaction_date', config('app.transaction_date')->month)
                        ->select(["id", "reference_number", "deposit", "withdrawal", "transaction_date", "transaction_datetime"]);
                    $imprests = $savings->union(
                        Imprest::query()
                            ->withoutGlobalScopes()
                            ->whereNotNull("withdrawal")
                            ->whereCashierId(auth()->id())
                            ->whereMonth('transaction_date', config('app.transaction_date')->month)
                            ->select(["id", "reference_number", "deposit", "withdrawal", "transaction_date", "transaction_datetime"])
                    );

                    $revolving_fund_replenishments = RevolvingFundReplenishment::query()
                        ->whereCashierId(auth()->id())
                        ->selectRaw("id,reference_number, amount as deposit, null as withdrawal, transaction_date,  transaction_datetime")
                        ->whereMonth('transaction_date', config('app.transaction_date')->month)
                        ->union($imprests);

                    return $revolving_fund_replenishments;
                }
            )
            ->defaultSort('transaction_datetime');
    }
}
