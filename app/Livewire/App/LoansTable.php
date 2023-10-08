<?php

namespace App\Livewire\App;

use App\Models\Loan;
use App\Models\LoanType;
use App\Models\Member;
use App\Models\SystemConfiguration;
use App\Oxytoxin\LoansProvider;
use Awcodes\FilamentTableRepeater\Components\TableRepeater;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Grid;
use Filament\Forms\Components\KeyValue;
use Filament\Forms\Components\Placeholder;
use Filament\Forms\Components\Repeater;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Forms\Contracts\HasForms;
use Filament\Notifications\Notification;
use Filament\Tables;
use Filament\Tables\Actions\Action;
use Filament\Tables\Actions\CreateAction;
use Filament\Tables\Actions\ViewAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Concerns\InteractsWithTable;
use Filament\Tables\Contracts\HasTable;
use Filament\Tables\Table;
use Livewire\Component;
use Illuminate\Contracts\View\View;
use Illuminate\Database\Eloquent\Builder;
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
                //
            ])
            ->actions([
                ViewAction::make()
                    ->modalContent(fn ($record) => view('filament.app.views.loan-payments', ['loan' => $record])),
                Action::make('Pay')
                    ->icon('heroicon-o-banknotes')
                    ->form([
                        Select::make('type')
                            ->options([
                                'OR' => 'OR',
                                'JV' => 'JV',
                                'CV' => 'CV',
                            ])
                            ->default('JV')
                            ->selectablePlaceholder(false)
                            ->live()
                            ->required(),
                        TextInput::make('reference_number')->required(),
                        TextInput::make('amount')->required()->numeric()->minValue(1)->prefix('P'),
                        TextInput::make('remarks'),
                        DatePicker::make('transaction_date')->default(today())->native(false)->label('Date'),
                    ])
                    ->action(function ($record, $data) {
                        $record->payments()->create($data);
                        Notification::make()->title('Payment made for loan!')->success()->send();
                    })
            ])
            ->headerActions([
                CreateAction::make()
                    ->fillForm([
                        'number_of_terms' => LoansProvider::LOAN_TERMS[12],
                        'deductions' => SystemConfiguration::first()?->content,
                        'transaction_date' => today(),
                        'release_date' => today(),
                    ])
                    ->form([
                        Select::make('loan_type_id')
                            ->relationship('loan_type', 'name')
                            ->live()
                            ->required(),
                        TextInput::make('reference_number')->required(),
                        DatePicker::make('transaction_date')->required()->native(false),
                        TextInput::make('gross_amount')->required()->numeric()->prefix('PHP')->live(true)->afterStateUpdated(function ($state, $set, $get) {
                            foreach ($get('deductions') as $key => $deduction) {
                                $set("deductions.$key.amount", round($state * $deduction['percentage'] / 100, 2));
                            }
                        }),
                        Select::make('number_of_terms')
                            ->options(LoansProvider::LOAN_TERMS)
                            ->live(),
                        Grid::make(3)
                            ->schema([
                                Placeholder::make('interest_rate')
                                    ->content(fn ($get) => str(LoanType::find($get('loan_type_id'))?->interest * 100 ?? 0)->append('%')->toString()),
                                Placeholder::make('interest')
                                    ->content(fn ($get) => format_money(LoansProvider::computeInterest($get('gross_amount'), LoanType::find($get('loan_type_id')), $get('number_of_terms')), 'PHP')),
                                Placeholder::make('monthly_payment')
                                    ->content(fn ($get) => format_money(LoansProvider::computeMonthlyPayment($get('gross_amount'), LoanType::find($get('loan_type_id')), $get('number_of_terms')), 'PHP')),
                            ]),
                        TableRepeater::make('deductions')
                            ->schema([
                                TextInput::make('name'),
                                TextInput::make('percentage')->formatStateUsing(fn ($state) => $state * 100)
                                    ->live(true)
                                    ->afterStateUpdated(fn ($set, $get, $state) => $set('amount', $get('../../gross_amount') * $state / 100))
                                    ->dehydrateStateUsing(fn ($state)  => $state / 100),
                                TextInput::make('amount')->readOnly()->numeric()->prefix('PHP')
                            ])
                            ->hideLabels(),
                        // TableRepeater::make('other_deductions')
                        //     ->schema([
                        //         TextInput::make('name'),
                        //         TextInput::make('amount')->numeric()->prefix('PHP')
                        //     ])
                        //     ->hideLabels(),
                        Placeholder::make('deductions_amount')
                            ->content(fn ($get) => format_money(collect($get('deductions'))->sum('amount'), 'PHP')),
                        DatePicker::make('release_date')->required()->native(false),
                    ])
                    ->action(function ($data) {
                        $loanType = LoanType::find($data['loan_type_id']);
                        Loan::create([
                            ...$data,
                            'interest_rate' => $loanType->interest,
                            'interest' => LoansProvider::computeInterest($data['gross_amount'], $loanType, $data['number_of_terms']),
                            'member_id' => $this->member->id,
                            'monthly_payment' => LoansProvider::computeMonthlyPayment($data['gross_amount'], $loanType, $data['number_of_terms']),
                            'deductions_amount' => collect($data['deductions'])->sum('amount')
                        ]);
                        Notification::make()->title('New loan created.')->success()->send();
                    })
                    ->createAnother(false),
                ViewAction::make('subsidiary_ledger')
                    ->icon('heroicon-o-clipboard-document-list')
                    ->label('Subsidiary Ledger')
                    ->url(route('filament.app.resources.members.loan-subsidiary-ledger', ['member' => $this->member]))
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
