<?php

    namespace App\Filament\App\Resources\MemberResource\Pages;

    use App\Filament\App\Pages\Cashier\Reports\HasSignatories;
    use App\Filament\App\Resources\MemberResource;
    use App\Models\Saving;
    use App\Models\SavingsAccount;
    use App\Models\SignatureSet;
    use Filament\Actions\Action;
    use Filament\Forms\Components\Textarea;
    use Filament\Notifications\Notification;
    use Filament\Resources\Pages\Page;
    use Filament\Tables\Concerns\InteractsWithTable;
    use Filament\Tables\Contracts\HasTable;
    use Filament\Tables\Enums\FiltersLayout;
    use Filament\Tables\Table;
    use Illuminate\Contracts\Support\Htmlable;
    use Malzariey\FilamentDaterangepickerFilter\Filters\DateRangeFilter;

    class SavingsSubsidiaryLedger extends Page implements HasTable
    {
        use HasSignatories, InteractsWithTable;

        protected static string $resource = MemberResource::class;

        protected string $view = 'filament.app.resources.member-resource.pages.savings-subsidiary-ledger';

        public SavingsAccount $savings_account;

        public function getHeading(): string|Htmlable
        {
            return 'Savings Subsidiary Ledger';
        }

        protected function getSignatureSet()
        {
            return SignatureSet::where('name', 'SL Reports')->first();
        }

        public function table(Table $table): Table
        {
            return $table
                ->query(Saving::whereBelongsTo($this->savings_account, 'savings_account'))
                ->content(fn() => view('filament.app.views.savings-sl', ['savings_account' => $this->savings_account, 'signatories' => $this->signatories]))
                ->filters([
                    DateRangeFilter::make('transaction_date')
                        ->format('m/d/Y')
                        ->defaultToday()
                        ->displayFormat('MM/DD/YYYY'),
                ])
                ->filtersLayout(FiltersLayout::AboveContent)
                ->headerActions([
                    Action::make('close_account')
                        ->label('Close Account')
                        ->color('danger')
                        ->icon('heroicon-o-lock-closed')
                        ->visible(fn() => $this->savings_account->isActive())
                        ->requiresConfirmation()
                        ->modalHeading('Close Savings Account')
                        ->modalDescription(fn() => 'Are you sure you want to close savings account '.$this->savings_account->number.'?')
                        ->schema([
                            Textarea::make('remarks')
                                ->label('Remarks')
                                ->placeholder('Optional reason for closing this account'),
                        ])
                        ->action(function (array $arguments) {
                            $this->savings_account->close($arguments['data']['remarks'] ?? null);
                            Notification::make()
                                ->title('Savings account closed successfully.')
                                ->success()
                                ->send();
                        }),
                    Action::make('back')
                        ->extraAttributes(['wire:ignore' => true])
                        ->label('Back to Savings')
                        ->url(route('filament.app.resources.members.view', ['record' => $this->savings_account->member, 'tab' => 'mso::tab', 'mso_type' => 1])),
                ])
                ->paginated(['all']);
        }
    }
