<?php

    namespace App\Filament\App\Resources\LoanApplicationResource\Pages;

    use Filament\Forms\Components\Repeater;
    use Filament\Schemas\Schema;
    use Filament\Schemas\Components\Fieldset;
    use Filament\Schemas\Components\Grid;
    use Filament\Schemas\Components\Section;
    use App\Filament\App\Resources\LoanApplicationResource;
    use App\Models\CreditAndBackgroundInvestigation;
    use App\Models\LoanApplication;
    use Filament\Forms\Components\DatePicker;
    use Filament\Forms\Components\KeyValue;
    use Filament\Forms\Components\TagsInput;
    use Filament\Forms\Components\TextInput;
    use Filament\Notifications\Notification;
    use Filament\Resources\Pages\Page;

    class CreditAndBackgroundInvestigationForm extends Page
    {
        protected static string $resource = LoanApplicationResource::class;

        protected string $view = 'filament.app.resources.loan-application-resource.pages.credit-and-background-investigation-form';

        public $data = [];

        public LoanApplication $loan_application;

        public CreditAndBackgroundInvestigation $cibi;

        public function form(Schema $schema): Schema
        {
            if ($this->loan_application->loan_type_id == 5) {
                return $this->kabuhayanForm($schema);
            }

            return $this->genericForm($schema);
        }

        private function kabuhayanForm(Schema $schema)
        {
            return $schema
                ->components([
                    Fieldset::make('personal_information')
                        ->schema([
                            TextInput::make('name_of_spouse'),
                            TextInput::make('number_of_dependents')->numeric(),
                            Grid::make(3)
                                ->schema([
                                    TextInput::make('elementary')->numeric(),
                                    TextInput::make('high_school')->numeric(),
                                    TextInput::make('college')->numeric(),
                                ]),
                        ]),
                    Fieldset::make('financial_capability')
                        ->schema([
                            TextInput::make('financial_capability.main_income_source'),
                            TagsInput::make('financial_capability.other_income_sources')->placeholder('New source'),
                            TextInput::make('financial_capability.total_income')->numeric(),
                        ]),
                    Fieldset::make('project_information')
                        ->schema([
                            TextInput::make('project_information.name'),
                            DatePicker::make('project_information.date_started')
                                ->native(false),
                            TextInput::make('project_information.commodities'),
                            TextInput::make('project_information.starting_capital')->numeric(),
                            TextInput::make('project_information.present_capital')->numeric(),
                            TextInput::make('project_information.monthly_business_income')->numeric(),
                            TagsInput::make('project_information.business_expenses')->placeholder('New expense'),
                        ]),
                    Repeater::make('assets')
                        ->table([
                            Repeater\TableColumn::make('Name'),
                            Repeater\TableColumn::make('Value'),
                            Repeater\TableColumn::make('Status'),
                        ])
                        ->schema([
                            TextInput::make('name'),
                            TextInput::make('value'),
                            TextInput::make('status'),
                        ])
                        ->default([]),
                    Repeater::make('existing_structure')
                        ->table([
                            Repeater\TableColumn::make('Name'),
                            Repeater\TableColumn::make('Rate'),
                        ])
                        ->schema([
                            TextInput::make('name'),
                            TextInput::make('rate'),
                        ])
                        ->default([]),
                    TextInput::make('collateral'),
                ])
                ->statePath('data');
        }

        private function genericForm(Schema $schema)
        {
            return $schema->components([
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
                            ])->columnSpan(1),
                    ]),
                Repeater::make('children')
                    ->schema([
                        TextInput::make('name'),
                        DatePicker::make('birthdate')->time(false)->native(false),
                        TextInput::make('course_and_school'),
                    ])
                    ->default([])
                    ->table([
                        Repeater\TableColumn::make('Name'),
                        Repeater\TableColumn::make('Birthdate'),
                        Repeater\TableColumn::make('Course and School'),
                    ]),
                Repeater::make('assets')
                    ->schema([
                        TextInput::make('name'),
                        TextInput::make('value'),
                        TextInput::make('status'),
                    ])
                    ->default([])
                    ->table([
                        Repeater\TableColumn::make('Name'),
                        Repeater\TableColumn::make('Value'),
                        Repeater\TableColumn::make('Status'),
                    ]),
                Repeater::make('employment_verification')
                    ->table([
                        Repeater\TableColumn::make('Particulars'),
                        Repeater\TableColumn::make('Borrower'),
                        Repeater\TableColumn::make('Co-Borrower 1'),
                        Repeater\TableColumn::make('Co-Borrower 2'),
                    ])
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
                Repeater::make('income_verification')
                    ->table([
                        Repeater\TableColumn::make('Particulars'),
                        Repeater\TableColumn::make('Borrower'),
                        Repeater\TableColumn::make('Co-Borrower 1'),
                        Repeater\TableColumn::make('Co-Borrower 2'),
                    ])
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
                'details' => $this->form->getState(),
            ]);
            Notification::make()->title('Saved!')->success()->send();
        }

        private function keyvaluefield($name, $key)
        {
            return KeyValue::make($key.'.'.$name)
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
                'loan_application_id' => $this->loan_application->id,
            ]);
            $this->form->fill();
            if ($this->cibi->details) {
                $this->data = $this->cibi->details;
            } else {

            }
            $comaker_members = $this->loan_application->load('comakers.member')->comakers->pluck('member');
            $loan_members = collect([$this->loan_application->member])->merge($comaker_members);
            $income_verification = collect($this->data['income_verification'] ?? [])->map(function ($item) use ($loan_members) {
                if ($item['particulars'] == 'Annual Income') {
                    return $this->fillRepeaterComakers($item['particulars'], $loan_members->pluck('annual_income')->toArray());
                }
                return $item;
            });
            $employment_verification = collect($this->data['employment_verification'] ?? [])->map(function ($item) use ($loan_members) {
                if ($item['particulars'] == 'Employer') {
                    return $this->fillRepeaterComakers($item['particulars'], $loan_members->pluck('present_employer')->toArray());
                }
                return $item;
            });
            $this->data['income_verification'] = $income_verification->toArray();
            $this->data['employment_verification'] = $employment_verification->toArray();

        }

        private function fillRepeaterComakers(string $array_key, array $values)
        {
            $keys = ['borrower', 'coborrower_1', 'coborrower_2'];

            $result = [
                'particulars' => $array_key,
                'borrower' => null,
                'coborrower_1' => null,
                'coborrower_2' => null,
            ];

            foreach ($values as $i => $value) {
                if (isset($keys[$i])) {
                    $result[$keys[$i]] = $value;
                }
            }

            return $result;
        }
    }
