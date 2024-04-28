<?php

namespace App\Filament\App\Resources\LoanApplicationResource\Pages;

use App\Filament\App\Resources\LoanApplicationResource;
use App\Models\CreditAndBackgroundInvestigation;
use App\Models\LoanApplication;
use Awcodes\FilamentTableRepeater\Components\TableRepeater;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Fieldset;
use Filament\Forms\Components\Grid;
use Filament\Forms\Components\KeyValue;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\TagsInput;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Form;
use Filament\Notifications\Notification;
use Filament\Resources\Pages\Page;

class CreditAndBackgroundInvestigationForm extends Page
{
    protected static string $resource = LoanApplicationResource::class;

    protected static string $view = 'filament.app.resources.loan-application-resource.pages.credit-and-background-investigation-form';

    public $data = [];

    public LoanApplication $loan_application;
    public CreditAndBackgroundInvestigation $cibi;

    public function form(Form $form): Form
    {
        if ($this->loan_application->loan_type_id == 5) {
            return $this->kabuhayanForm($form);
        }
        return $this->genericForm($form);
    }

    private function kabuhayanForm(Form $form)
    {
        return $form
            ->schema([
                Fieldset::make('personal_information')
                    ->schema([
                        TextInput::make('name_of_spouse'),
                        TextInput::make('number_of_dependents')->numeric(),
                        Grid::make(3)
                            ->schema([
                                TextInput::make('elementary')->numeric(),
                                TextInput::make('high_school')->numeric(),
                                TextInput::make('college')->numeric(),
                            ])
                    ]),
                Fieldset::make('financial_capability')
                    ->schema([
                        TextInput::make('main_income_source'),
                        TagsInput::make('other_income_sources')->placeholder('New source'),
                        TextInput::make('total_income')->numeric(),
                    ]),
                Fieldset::make('project_information')
                    ->schema([
                        TextInput::make('name'),
                        DatePicker::make('date_started')
                            ->native(false),
                        TextInput::make('commodities'),
                        TextInput::make('starting_capital')->numeric(),
                        TextInput::make('present_capital')->numeric(),
                        TextInput::make('monthly_business_income')->numeric(),
                        TagsInput::make('business_expenses')->placeholder('New expense'),
                    ]),
                TableRepeater::make('assets')
                    ->hideLabels()
                    ->schema([
                        TextInput::make('name'),
                        TextInput::make('value'),
                        TextInput::make('status'),
                    ])
                    ->default([]),
                TableRepeater::make('existing_structure')
                    ->hideLabels()
                    ->schema([
                        TextInput::make('name'),
                        TextInput::make('rate'),
                    ])
                    ->default([]),
                TextInput::make('collateral'),
            ])
            ->statePath('data');
    }

    private function genericForm(Form $form)
    {
        return $form->schema([
            Section::make('Basic Information')
                ->columns(2)
                ->schema([
                    Section::make('Borrower')
                        ->schema([
                            TextInput::make('borrower.nickname'),
                            TextInput::make('borrower.nationality'),
                            TextInput::make('borrower.school'),
                        ])->columnSpan(1),
                    Section::make('Spouse')
                        ->schema([
                            TextInput::make('spouse.name'),
                            TextInput::make('spouse.nickname'),
                            TextInput::make('spouse.middle_name'),
                            TextInput::make('spouse.date_of_birth'),
                            TextInput::make('spouse.age'),
                            TextInput::make('spouse.contact_number'),
                            TextInput::make('spouse.civil_status'),
                            TextInput::make('spouse.nationality'),
                            TextInput::make('spouse.address'),
                            TextInput::make('spouse.highest_educational_attainment'),
                            TextInput::make('spouse.school'),
                        ])->columnSpan(1)
                ]),
            TableRepeater::make('children')
                ->schema([
                    TextInput::make('name'),
                    DatePicker::make('birthdate')->time(false)->native(false),
                    TextInput::make('course_and_school'),
                ])
                ->default([])
                ->hideLabels(),
            TableRepeater::make('assets')
                ->schema([
                    TextInput::make('name'),
                    TextInput::make('value'),
                    TextInput::make('status'),
                ])
                ->default([])
                ->hideLabels(),
            TableRepeater::make('employment_verification')
                ->hideLabels()
                ->schema([
                    TextInput::make('particulars')->readOnly(),
                    TextInput::make('borrower'),
                    TextInput::make('coborrower_1'),
                    TextInput::make('coborrower_2'),
                ])
                ->default([
                    ['particulars' => 'Employer'],
                    ['particulars' => 'Office Address'],
                    ['particulars' => 'Business Form'],
                    ['particulars' => 'Nature of Business'],
                    ['particulars' => 'Year Connected'],
                    ['particulars' => 'Position'],
                    ['particulars' => 'Status of Employment'],
                ])->addable(false)->deletable(false)->reorderable(false),
            TableRepeater::make('income_verification')
                ->hideLabels()
                ->schema([
                    TextInput::make('particulars')->readOnly(),
                    TextInput::make('borrower'),
                    TextInput::make('coborrower_1'),
                    TextInput::make('coborrower_2'),
                ])
                ->default([
                    ['particulars' => 'Basic Salary'],
                    ['particulars' => 'Allowances'],
                    ['particulars' => 'Business Income'],
                    ['particulars' => 'Other Income'],
                    ['particulars' => 'Monthly Income'],
                    ['particulars' => 'Annual Income'],
                ])->addable(false)->deletable(false)->reorderable(false),
        ])->statePath('data');
    }

    public function save()
    {
        $this->cibi->update([
            'details' => $this->form->getState()
        ]);
        Notification::make()->title('Saved!')->success()->send();
    }
    private function keyvaluefield($name, $key)
    {
        return KeyValue::make($key . '.' . $name)
            ->deletable(false)
            ->addable(false)
            ->editableKeys(false)
            ->default([
                'Borrower' => '',
                'Co-Borrower 1' => '',
                'Co-Borrower 2' => '',
            ]);
    }

    public function mount()
    {
        $this->cibi = CreditAndBackgroundInvestigation::query()->firstOrCreate([
            'loan_application_id' => $this->loan_application->id
        ]);
        $this->form->fill();
        if ($this->cibi->details) {
            $this->data = $this->cibi->details;
        }
    }
}
