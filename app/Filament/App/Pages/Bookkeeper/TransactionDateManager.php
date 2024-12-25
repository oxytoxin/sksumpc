<?php

namespace App\Filament\App\Pages\Bookkeeper;

use App\Models\SystemConfiguration;
use App\Models\TransactionDateHistory;
use Carbon\Carbon;
use DB;
use Filament\Actions\Concerns\InteractsWithActions;
use Filament\Actions\Contracts\HasActions;
use Filament\Forms\Components\Actions;
use Filament\Forms\Components\Actions\Action;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Placeholder;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Forms\Contracts\HasForms;
use Filament\Forms\Form;
use Filament\Notifications\Notification;
use Filament\Pages\Page;

class TransactionDateManager extends Page implements HasActions, HasForms
{
    use InteractsWithActions, InteractsWithForms;

    protected static ?int $navigationSort = 1;

    protected static ?string $navigationGroup = 'Bookkeeping';

    protected static string $view = 'filament.app.pages.bookkeeper.transaction-date-manager';

    public $transaction_date;

    public static function shouldRegisterNavigation(): bool
    {
        return auth()->user()->can('manage bookkeeping');
    }

    public function form(Form $form): Form
    {
        return $form->schema([
            Placeholder::make('current_transaction_date')
                ->content(fn () => SystemConfiguration::config('Transaction Date')?->content['transaction_date']),
            Placeholder::make('note')
                ->content(fn ($get) => $get('transaction_date') ? 'All transactions date will be set to '.Carbon::create($get('transaction_date'))->format('m/d/Y') : "No transaction date set for today's transactions."),
            DatePicker::make('transaction_date')
                ->native(false)
                ->unique('transaction_date_histories', 'date')
                ->validationMessages([
                    'unique' => 'This date has already been used in the past.',
                ])
                ->default(TransactionDateHistory::current_date())
                ->required()
                ->live(),
            Actions::make([
                Action::make('set')
                    ->label('Set Date')
                    ->action(function () {
                        $this->form->validate();
                        DB::beginTransaction();
                        TransactionDateHistory::query()->update([
                            'is_current' => false,
                        ]);
                        TransactionDateHistory::create([
                            'date' => $this->transaction_date,
                            'is_current' => true,
                        ]);
                        DB::commit();
                        Notification::make()->title('Transaction date set!')->success()->send();
                    })
                    ->color('success'),
                Action::make('clear')
                    ->label('Close Date')
                    ->action(function () {
                        TransactionDateHistory::query()->update([
                            'is_current' => false,
                        ]);
                        SystemConfiguration::config('Transaction Date')?->delete();
                        $this->reset();
                        Notification::make()->title('Transaction date cleared!')->success()->send();
                    })
                    ->requiresConfirmation()
                    ->color('danger'),
            ]),
        ]);
    }
}
