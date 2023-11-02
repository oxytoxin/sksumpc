<?php

namespace App\Filament\App\Resources\MemberResource\Pages;

use App\Models\LoanType;
use Filament\Forms\Form;
use Filament\Support\RawJs;
use App\Oxytoxin\LoansProvider;
use Filament\Resources\Pages\Page;
use Filament\Forms\Components\Grid;
use Filament\Forms\Components\Hidden;
use Filament\Forms\Components\Select;
use Filament\Forms\Contracts\HasForms;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Placeholder;
use App\Filament\App\Resources\MemberResource;
use App\Models\Loan;
use App\Models\Member;
use Filament\Forms\Concerns\InteractsWithForms;
use Awcodes\FilamentTableRepeater\Components\TableRepeater;
use Filament\Forms\Components\Actions;
use Filament\Forms\Components\Actions\Action;
use Filament\Forms\Components\Section;
use Filament\Notifications\Notification;

use function Filament\Support\format_money;

class EditMemberLoan extends Page implements HasForms
{
    use InteractsWithForms;

    public Member $member;
    public Loan $loan;
    public ?array $data;

    protected static string $resource = MemberResource::class;

    protected static string $view = 'filament.app.resources.member-resource.pages.edit-member-loan';

    public function mount()
    {
        $this->form->fill([
            'number_of_terms' => $this->loan->number_of_terms,
            'transaction_date' => $this->loan->transaction_date,
            'release_date' => $this->loan->release_date,
            'gross_amount' => $this->loan->gross_amount,
            'deductions' => $this->loan->deductions,
            'reference_number' => $this->loan->reference_number,
        ]);
    }

    public function form(Form $form): Form
    {
        return $form
            ->statePath('data')
            ->schema([
                Select::make('loan_type_id')
                    ->model($this->loan)
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
                    ->live(true)->afterStateUpdated(function ($state, $set, $get) {
                        if ($loanType = LoanType::find($get('loan_type_id'))) {
                            $deductions = LoansProvider::computeDeductions($loanType, $state, $this->member, $this->loan->id);
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
                DatePicker::make('release_date')->required()->native(false),

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
                    ->columnSpanFull()
                    ->schema([
                        TextInput::make('name')->readOnly(fn ($get) => boolval($get('readonly'))),
                        TextInput::make('amount')
                            ->numeric()
                            ->prefix('P')
                            ->readOnly(fn ($get) => boolval($get('readonly'))),
                        Hidden::make('readonly')->default(false),
                    ])
                    ->live(true)
                    ->orderColumn(false)
                    ->hideLabels(),
                // Grid::make(2)
                //     ->schema([
                //         Placeholder::make('deductions_amount')
                //             ->content(fn ($get) => format_money(collect($get('deductions'))->map(function ($d) {
                //                 $d['amount'] = str_replace(',', '', filled($d['amount']) ? $d['amount'] : 0);
                //                 return $d;
                //             })->sum('amount'), 'PHP')),
                //         Placeholder::make('net_amount')
                //             ->content(fn ($get) => format_money(floatval(str_replace(',', '', $get('gross_amount') ?? 0)) - collect($get('deductions'))->map(function ($d) {
                //                 $d['amount'] = str_replace(',', '', filled($d['amount']) ? $d['amount'] : 0);
                //                 return $d;
                //             })->sum('amount'), 'PHP')),
                //     ]),
                Actions::make([
                    Action::make('save')
                        ->form([])
                        ->action(function ($data) {
                            $loanType = LoanType::find($data['loan_type_id']);
                            if ($this->loan->posted)
                                return Notification::make()->title('Update failed')->body('Loan was already posted by bookkeeper.')->danger()->success()->send();
                            $this->loan->update([
                                ...$data,
                                'interest_rate' => $loanType->interest_rate,
                                'interest' => LoansProvider::computeInterest($data['gross_amount'], $loanType, $data['number_of_terms'], $data['transaction_date']),
                                'member_id' => $this->member->id,
                                'monthly_payment' => LoansProvider::computeMonthlyPayment($data['gross_amount'], $loanType, $data['number_of_terms'], $data['transaction_date']),
                            ]);
                            $this->dispatch('refresh');
                            Notification::make()->title('Loan updated.')->success()->send();
                        })
                ])
            ])->columns(2);
    }
}
