<?php

namespace App\Filament\App\Pages\Bookkeeper;

use App\Models\SystemConfiguration;
use Carbon\Carbon;
use Filament\Actions\Concerns\InteractsWithActions;
use Filament\Actions\Contracts\HasActions;
use Filament\Forms\Components\Actions;
use Filament\Forms\Components\Actions\Action;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Placeholder;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Forms\Contracts\HasForms;
use Filament\Forms\Form;
use Filament\Pages\Page;

class TransactionDateManager extends Page implements HasActions, HasForms
{
    use InteractsWithActions, InteractsWithForms;

    protected static ?int $navigationSort = 1;

    protected static ?string $navigationGroup = 'Bookkeeping';

    protected static string $view = 'filament.app.pages.bookkeeper.transaction-date-manager';

    public $transaction_date;

    public function form(Form $form): Form
    {
        return $form->schema([
            Placeholder::make('current_transaction_date')
                ->content(fn () => SystemConfiguration::config('Transaction Date')?->content['transaction_date']),
            Placeholder::make('note')
                ->content(fn ($get) => $get('transaction_date') ? "All transactions date will be set to " . Carbon::create($get('transaction_date'))->format('m/d/Y') : "No transaction date set for today's transactions."),
            DatePicker::make('transaction_date')
                ->native(false)
                ->required()
                ->live(),
            Actions::make([
                Action::make('set')
                    ->action(function () {
                        $this->form->validate();
                        SystemConfiguration::where('name', 'Transaction Date')->delete();
                        SystemConfiguration::create([
                            'name' => 'Transaction Date',
                            'content' => [
                                'transaction_date' => $this->transaction_date
                            ]
                        ]);
                    })
                    ->color('success'),
                Action::make('clear')
                    ->action(function () {
                        SystemConfiguration::config('Transaction Date')?->delete();
                        $this->reset();
                    })
                    ->requiresConfirmation()
                    ->color('danger')
            ])
        ]);
    }
}
