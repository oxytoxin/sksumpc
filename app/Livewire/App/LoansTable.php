<?php

namespace App\Livewire\App;

use App\Models\Loan;
use App\Models\LoanType;
use App\Models\Member;
use App\Models\SystemConfiguration;
use App\Oxytoxin\LoansProvider;
use App\Oxytoxin\OverrideProvider;
use Awcodes\FilamentTableRepeater\Components\TableRepeater;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Grid;
use Filament\Forms\Components\Hidden;
use Filament\Forms\Components\KeyValue;
use Filament\Forms\Components\Placeholder;
use Filament\Forms\Components\Repeater;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Forms\Contracts\HasForms;
use Filament\Notifications\Notification;
use Filament\Support\RawJs;
use Filament\Tables;
use Filament\Tables\Actions\Action;
use Filament\Tables\Actions\ActionGroup;
use Filament\Tables\Actions\CreateAction;
use Filament\Tables\Actions\DeleteAction;
use Filament\Tables\Actions\EditAction;
use Filament\Tables\Actions\ViewAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Concerns\InteractsWithTable;
use Filament\Tables\Contracts\HasTable;
use Filament\Tables\Enums\FiltersLayout;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;
use Livewire\Component;
use Illuminate\Contracts\View\View;
use Illuminate\Database\Eloquent\Builder;
use PDO;
use Str;
use function Filament\Support\format_money;

class LoansTable extends Component implements HasForms, HasTable
{
    use InteractsWithForms;
    use InteractsWithTable;

    public Member $member;

    public function table(Table $table): Table
    {
        return $table
            ->query(Loan::whereMemberId($this->member->id))
            ->columns([
                TextColumn::make('reference_number'),
                TextColumn::make('loan_type.code'),
                // TextColumn::make('deductions_list')
                //     ->listWithLineBreaks(),
                TextColumn::make('number_of_terms'),
                TextColumn::make('gross_amount')->money('PHP'),
                TextColumn::make('interest')->money('PHP'),
                TextColumn::make('deductions_amount')->money('PHP'),
                TextColumn::make('net_amount')->money('PHP'),
                TextColumn::make('monthly_payment')->money('PHP'),
                TextColumn::make('outstanding_balance')->money('PHP'),
                TextColumn::make('transaction_date')->date('F d, Y')
            ])
            ->filters([
                SelectFilter::make('status')
                    ->options([
                        'ongoing' => 'On-going',
                        'paid' => 'Paid',
                        'pending' => 'Pending',
                    ])
                    ->query(function (Builder $query, $data) {
                        $query
                            ->when($data['value'] == 'paid', fn ($query) => $query->where('outstanding_balance', '<=', 0)->posted())
                            ->when($data['value'] == 'ongoing', fn ($query) => $query->where('outstanding_balance', '>', 0)->posted())
                            ->when($data['value'] == 'pending', fn ($query) => $query->pending());
                    })
            ], layout: FiltersLayout::AboveContent)
            ->actions([
                EditAction::make('edit')
                    ->hidden(fn ($record) => $record->posted || $record->created_at->isBefore(today()->subDay()))
                    ->mountUsing(function ($form, $record, $livewire) {
                        $form->fill([
                            'number_of_terms' => $record->number_of_terms,
                            'transaction_date' => $record->transaction_date,
                            'release_date' => $record->release_date,
                            'gross_amount' => $record->gross_amount,
                            'reference_number' => $record->reference_number,
                        ]);
                        $livewire->mountedTableActionsData[0]['deductions'] = $record->deductions;
                    })
                    ->form([
                        TextInput::make('passkey')
                            ->required()
                            ->hint("Manager's Password")
                            ->password(),
                        Select::make('loan_type_id')
                            ->relationship('loan_type', 'name')
                            ->live()
                            ->afterStateUpdated(function ($state, $set, $get, $record) {
                                if ($loanType = LoanType::find($state)) {
                                    $deductions = LoansProvider::computeDeductions($loanType, str_replace(',', '', $get('gross_amount') ?? 0), $this->member, $record->id);
                                    $set('deductions', $deductions);
                                }
                            })
                            ->required(),
                        TextInput::make('reference_number')->required()
                            ->unique('loans', 'reference_number', ignoreRecord: true),
                        DatePicker::make('transaction_date')->required()->native(false),
                        TextInput::make('gross_amount')->required()
                            ->afterStateUpdated(function ($state, $set, $get, $record) {
                                if ($loanType = LoanType::find($get('loan_type_id'))) {
                                    $deductions = LoansProvider::computeDeductions($loanType, $state, $this->member, $record->id);
                                    $deductions = collect($deductions)->map(function ($d) {
                                        $d['amount'] = number_format($d['amount'], 2);
                                        return $d;
                                    })->toArray();
                                    $set('deductions', $deductions);
                                }
                            })
                            ->moneymask(),
                        Select::make('number_of_terms')
                            ->options(LoansProvider::LOAN_TERMS)
                            ->live(),
                        Grid::make(3)
                            ->schema([
                                Placeholder::make('interest_rate')
                                    ->content(fn ($get) => str(LoanType::find($get('loan_type_id'))?->interest_rate * 100 ?? 0)->append('%')->toString()),
                                Placeholder::make('interest')
                                    ->content(fn ($get) => format_money(LoansProvider::computeInterest(str_replace(',', '', $get('gross_amount') ?? 0), LoanType::find($get('loan_type_id')), $get('number_of_terms'), $get('transaction_date')), 'PHP')),
                                Placeholder::make('monthly_payment')
                                    ->content(fn ($get) => format_money(LoansProvider::computeMonthlyPayment(str_replace(',', '', $get('gross_amount') ?? 0), LoanType::find($get('loan_type_id')), $get('number_of_terms'), $get('transaction_date')), 'PHP')),
                            ]),
                        TableRepeater::make('deductions')
                            ->schema([
                                TextInput::make('name')->readOnly(fn ($get) => boolval($get('readonly'))),
                                TextInput::make('amount')
                                    ->moneymask()
                                    ->readOnly(fn ($get) => boolval($get('readonly'))),
                                Hidden::make('readonly')->default(false),
                            ])
                            ->live(true)
                            ->orderColumn(false)
                            ->hideLabels(),
                        Grid::make(2)
                            ->schema([
                                Placeholder::make('deductions_amount')
                                    ->content(fn ($get) => format_money(collect($get('deductions'))->map(function ($d) {
                                        $d['amount'] = str_replace(',', '', filled($d['amount']) ? $d['amount'] : 0);
                                        return $d;
                                    })->sum('amount'), 'PHP')),
                                Placeholder::make('net_amount')
                                    ->content(fn ($get) => format_money(floatval(str_replace(',', '', $get('gross_amount') ?? 0)) - collect($get('deductions'))->map(function ($d) {
                                        $d['amount'] = str_replace(',', '', filled($d['amount']) ? $d['amount'] : 0);
                                        return $d;
                                    })->sum('amount'), 'PHP')),
                            ]),
                        DatePicker::make('release_date')->required()->native(false),
                    ])
                    ->action(function ($data, $record) {
                        if (!OverrideProvider::promptManagerPasskey($data['passkey'])) return;
                        unset($data['passkey']);
                        $loanType = LoanType::find($data['loan_type_id']);
                        if ($record->posted)
                            return Notification::make()->title('Update failed')->body('Loan was already posted by bookkeeper.')->danger()->success()->send();
                        $record->update([
                            ...$data,
                            'interest_rate' => $loanType->interest_rate,
                            'interest' => LoansProvider::computeInterest($data['gross_amount'], $loanType, $data['number_of_terms'], $data['transaction_date']),
                            'member_id' => $this->member->id,
                            'monthly_payment' => LoansProvider::computeMonthlyPayment($data['gross_amount'], $loanType, $data['number_of_terms'], $data['transaction_date']),
                        ]);
                        $this->dispatch('refresh');
                        Notification::make()->title('Loan updated.')->success()->send();
                    }),
                DeleteAction::make()
                    ->hidden(fn ($record) => $record->posted),
                Action::make('Pay')
                    ->icon('heroicon-o-banknotes')
                    ->form([
                        Select::make('type')
                            ->paymenttype()
                            ->required(),
                        TextInput::make('reference_number')->required()
                            ->unique('loan_payments'),
                        TextInput::make('amount')->required()->numeric()->minValue(1)->prefix('P')->default(fn ($record) => $record->monthly_payment),
                        TextInput::make('remarks'),
                        DatePicker::make('transaction_date')->default(today())->native(false)->label('Date'),
                    ])
                    ->action(function ($record, $data) {
                        $record->payments()->create($data);
                        Notification::make()->title('Payment made for loan!')->success()->send();
                    })
                    ->visible(fn ($record) => $record->outstanding_balance > 0 && $record->posted),
                ActionGroup::make([
                    Action::make('payments')
                        ->icon('heroicon-o-currency-dollar')
                        ->visible(fn ($record) => $record->posted)
                        ->modalContent(fn ($record) => view('filament.app.views.loan-payments', ['loan' => $record])),
                    Action::make('amortization')
                        ->label('Amortization Schedule')
                        ->icon('heroicon-o-calendar-days')
                        ->visible(fn ($record) => $record->posted)
                        ->url(fn ($record) => route('filament.app.resources.members.loan-amortization-schedule', ['loan' => $record])),
                    Action::make('sl')
                        ->label('Subsidiary Ledger')
                        ->icon('heroicon-o-queue-list')
                        ->visible(fn ($record) => $record->posted)
                        ->url(fn ($record) => route('filament.app.resources.members.loan-subsidiary-ledger', ['loan' => $record])),
                    Action::make('ds')
                        ->label('Disclosure Sheet')
                        ->icon('heroicon-o-document')
                        ->visible(fn ($record) => $record->posted)
                        ->url(fn ($record) => route('filament.app.resources.members.loan-disclosure-sheet', ['loan' => $record])),
                ])
                    ->button()
                    ->outlined()
                    ->icon(false)
                    ->label('View')
            ])
            ->headerActions([
                CreateAction::make()
                    ->visible(auth()->user()->can('manage loans'))
                    ->fillForm(function () {
                        $gross_amount = match ($this->member->member_type_id) {
                            1 => ($this->member->capital_subscriptions()->sum('amount_subscribed') ?? 0) * 3,
                            default => ($this->member->capital_subscriptions()->sum('amount_subscribed') ?? 0) * 0.8
                        };
                        return [
                            'number_of_terms' => LoansProvider::LOAN_TERMS[12],
                            'transaction_date' => today(),
                            'release_date' => today(),
                            'gross_amount' => $gross_amount,
                        ];
                    })
                    ->form([
                        Select::make('loan_type_id')
                            ->relationship('loan_type', 'name')
                            ->live()
                            ->afterStateUpdated(function ($state, $set, $get) {
                                if ($loanType = LoanType::find($state)) {
                                    $deductions = LoansProvider::computeDeductions($loanType, str_replace(',', '', $get('gross_amount') ?? 0), $this->member);
                                    $set('deductions', $deductions);
                                }
                            })
                            ->required(),
                        TextInput::make('reference_number')->required()
                            ->unique('loans'),
                        DatePicker::make('transaction_date')->required()->native(false),
                        TextInput::make('gross_amount')->required()
                            ->afterStateUpdated(function ($state, $set, $get) {
                                if ($loanType = LoanType::find($get('loan_type_id'))) {
                                    $deductions = LoansProvider::computeDeductions($loanType, $state, $this->member);
                                    $deductions = collect($deductions)->map(function ($d) {
                                        $d['amount'] = number_format($d['amount'], 2);
                                        return $d;
                                    })->toArray();
                                    $set('deductions', $deductions);
                                }
                            })
                            ->moneymask(),
                        Select::make('number_of_terms')
                            ->options(LoansProvider::LOAN_TERMS)
                            ->live(),
                        Grid::make(3)
                            ->schema([
                                Placeholder::make('interest_rate')
                                    ->content(fn ($get) => str(LoanType::find($get('loan_type_id'))?->interest_rate * 100 ?? 0)->append('%')->toString()),
                                Placeholder::make('interest')
                                    ->content(fn ($get) => format_money(LoansProvider::computeInterest(str_replace(',', '', $get('gross_amount') ?? 0), LoanType::find($get('loan_type_id')), $get('number_of_terms'), $get('transaction_date')), 'PHP')),
                                Placeholder::make('monthly_payment')
                                    ->content(fn ($get) => format_money(LoansProvider::computeMonthlyPayment(str_replace(',', '', $get('gross_amount') ?? 0), LoanType::find($get('loan_type_id')), $get('number_of_terms'), $get('transaction_date')), 'PHP')),
                            ]),
                        TableRepeater::make('deductions')
                            ->schema([
                                TextInput::make('name')->readOnly(fn ($get) => boolval($get('readonly'))),
                                TextInput::make('amount')
                                    ->moneymask()
                                    ->readOnly(fn ($get) => boolval($get('readonly'))),
                                Hidden::make('readonly')->default(false),
                            ])
                            ->live(true)
                            ->orderColumn(false)
                            ->hideLabels(),
                        Grid::make(2)
                            ->schema([
                                Placeholder::make('deductions_amount')
                                    ->content(fn ($get) => format_money(collect($get('deductions'))->map(function ($d) {
                                        $d['amount'] = str_replace(',', '', filled($d['amount']) ? $d['amount'] : 0);
                                        return $d;
                                    })->sum('amount'), 'PHP')),
                                Placeholder::make('net_amount')
                                    ->content(fn ($get) => format_money(floatval(str_replace(',', '', $get('gross_amount') ?? 0)) - collect($get('deductions'))->map(function ($d) {
                                        $d['amount'] = str_replace(',', '', filled($d['amount']) ? $d['amount'] : 0);
                                        return $d;
                                    })->sum('amount'), 'PHP')),
                            ]),
                        DatePicker::make('release_date')->required()->native(false),
                    ])
                    ->action(function ($data) {
                        $loanType = LoanType::find($data['loan_type_id']);
                        Loan::create([
                            ...$data,
                            'interest_rate' => $loanType->interest_rate,
                            'interest' => LoansProvider::computeInterest($data['gross_amount'], $loanType, $data['number_of_terms'], $data['transaction_date']),
                            'member_id' => $this->member->id,
                            'monthly_payment' => LoansProvider::computeMonthlyPayment($data['gross_amount'], $loanType, $data['number_of_terms'], $data['transaction_date']),
                        ]);
                        $this->dispatch('refresh');
                        Notification::make()->title('New loan created.')->success()->send();
                    })
                    ->createAnother(false),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    //
                ]),
            ]);
    }

    public function render(): View
    {
        return view('livewire.app.loans-table');
    }
}
