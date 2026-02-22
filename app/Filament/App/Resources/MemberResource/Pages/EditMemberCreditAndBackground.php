<?php

    namespace App\Filament\App\Resources\MemberResource\Pages;

    use App\Data\ChildData;
    use App\Data\EmploymentVerificationData;
    use App\Data\IncomeVerificationData;
    use App\Data\SpouseData;
    use App\Filament\App\Resources\MemberResource;
    use App\Models\Member;
    use Filament\Forms\Components\DatePicker;
    use Filament\Forms\Components\Repeater;
    use Filament\Forms\Components\TextInput;
    use Filament\Forms\Concerns\InteractsWithForms;
    use Filament\Forms\Contracts\HasForms;
    use Filament\Notifications\Notification;
    use Filament\Resources\Pages\Page;
    use Filament\Schemas\Components\Actions;
    use Filament\Schemas\Components\Grid;
    use Filament\Schemas\Components\Section;
    use Filament\Schemas\Schema;

    class EditMemberCreditAndBackground extends Page implements HasForms
    {
        use InteractsWithForms;

        public Member $record;

        public ?array $data;

        protected static string $resource = MemberResource::class;

        protected static ?string $title = 'Edit Credit & Background';

        public static function shouldRegisterNavigation(array $parameters = []): bool
        {
            return false;
        }

        protected string $view = 'filament.app.resources.member-resource.pages.edit-member-credit-and-background';

        public function mount(): void
        {
            $this->form->fill($this->getData());
        }

        private function getData(): array
        {
            $cibi = $this->record->credit_and_background;
            return array_filter([
                'nickname' => $cibi?->nickname,
                'nationality' => $cibi?->nationality,
                'school' => $cibi?->school,
                ...$this->prefixKeys($cibi?->spouse?->toArray() ?? [], 'spouse_'),
                'children' => collect($cibi?->children ?? [])->map(fn(ChildData $child) => $child->toArray())->toArray(),
                ...($cibi?->employment_verification?->toArray() ?? []),
                ...($cibi?->income_verification?->toArray() ?? []),
            ]);
        }

        private function prefixKeys(array $array, string $prefix): array
        {
            return collect($array)
                ->mapWithKeys(fn($value, $key) => [$prefix.$key => $value])
                ->toArray();
        }

        public function form(Schema $schema): Schema
        {
            return $schema
                ->statePath('data')
                ->components([
                    Grid::make(3)
                        ->schema([
                            TextInput::make('nickname')->maxLength(125),
                            TextInput::make('nationality')->maxLength(125),
                            TextInput::make('school')->maxLength(125),
                        ]),
                    Section::make('Spouse Information')
                        ->schema([
                            Grid::make(3)
                                ->schema([
                                    TextInput::make('spouse_name')->label('Name')->maxLength(125),
                                    TextInput::make('spouse_nickname')->label('Nickname')->maxLength(125),
                                    TextInput::make('spouse_middle_name')->label('Middle Name')->maxLength(125),
                                ]),
                            Grid::make(3)
                                ->schema([
                                    DatePicker::make('spouse_date_of_birth')->label('Date of Birth')->native(false),
                                    TextInput::make('spouse_age')->label('Age')->numeric()->maxLength(3),
                                    TextInput::make('spouse_contact_number')->label('Contact Number')->maxLength(125),
                                ]),
                            Grid::make(3)
                                ->schema([
                                    TextInput::make('spouse_civil_status')->label('Civil Status')->maxLength(125),
                                    TextInput::make('spouse_nationality')->label('Nationality')->maxLength(125),
                                    TextInput::make('spouse_address')->label('Address')->maxLength(125),
                                ]),
                            Grid::make(2)
                                ->schema([
                                    TextInput::make('spouse_highest_educational_attainment')->label('Highest Educational Attainment')->maxLength(125),
                                    TextInput::make('spouse_school')->label('School')->maxLength(125),
                                ]),
                        ]),
                    Section::make('Children')
                        ->schema([
                            Repeater::make('children')
                                ->default([])
                                ->hiddenLabel()
                                ->table([
                                    Repeater\TableColumn::make('Name'),
                                    Repeater\TableColumn::make('Birthdate'),
                                    Repeater\TableColumn::make('Course and School'),
                                ])
                                ->schema([
                                    TextInput::make('name')->required()->maxLength(125),
                                    DatePicker::make('birthdate')->native(false)->required(),
                                    TextInput::make('course_and_school')->maxLength(255),
                                ])
                                ->columns(3)
                                ->itemLabel(fn(array $state): ?string => $state['name'] ?? null),
                        ]),
                    Section::make('Employment Verification')
                        ->schema([
                            Grid::make(3)
                                ->schema([
                                    TextInput::make('employer')->maxLength(125),
                                    TextInput::make('office_address')->maxLength(255),
                                    TextInput::make('business_form')->maxLength(125),
                                ]),
                            Grid::make(3)
                                ->schema([
                                    TextInput::make('nature_of_business')->label('Nature of Business')->maxLength(125),
                                    TextInput::make('year_connected')->label('Year Connected')->numeric()->maxLength(4),
                                    TextInput::make('position')->maxLength(125),
                                ]),
                            TextInput::make('employment_status')->maxLength(125),
                        ]),
                    Section::make('Income Verification')
                        ->schema([
                            Grid::make(3)
                                ->schema([
                                    TextInput::make('basic_salary')->numeric()->prefix('₱')->step(0.01),
                                    TextInput::make('allowances')->numeric()->prefix('₱')->step(0.01),
                                    TextInput::make('business_income')->numeric()->prefix('₱')->step(0.01),
                                ]),
                            Grid::make(3)
                                ->schema([
                                    TextInput::make('other_income')->numeric()->prefix('₱')->step(0.01),
                                    TextInput::make('monthly_income')->numeric()->prefix('₱')->step(0.01),
                                    TextInput::make('annual_income')->numeric()->prefix('₱')->step(0.01),
                                ]),
                        ]),
                    Actions::make([
                        \Filament\Actions\Action::make('save')
                            ->label('Save')
                            ->color('success')
                            ->keyBindings(['command+s', 'ctrl+s'])
                            ->action(function () {
                                $data = $this->form->getState();
                                $spouseData = new SpouseData(
                                    name: $data['spouse_name'] ?? null,
                                    nickname: $data['spouse_nickname'] ?? null,
                                    middle_name: $data['spouse_middle_name'] ?? null,
                                    date_of_birth: $data['spouse_date_of_birth'] ?? null,
                                    age: $data['spouse_age'] ?? null,
                                    contact_number: $data['spouse_contact_number'] ?? null,
                                    civil_status: $data['spouse_civil_status'] ?? null,
                                    nationality: $data['spouse_nationality'] ?? null,
                                    address: $data['spouse_address'] ?? null,
                                    highest_educational_attainment: $data['spouse_highest_educational_attainment'] ?? null,
                                    school: $data['spouse_school'] ?? null,
                                );

                                $childrenData = collect($data['children'] ?? [])
                                    ->map(fn($child) => new ChildData(
                                        name: $child['name'] ?? null,
                                        birthdate: $child['birthdate'] ?? null,
                                        course_and_school: $child['course_and_school'] ?? null,
                                    ))
                                    ->toArray();
                                $employmentVerificationData = new EmploymentVerificationData(
                                    employer: $data['employer'] ?? null,
                                    office_address: $data['office_address'] ?? null,
                                    business_form: $data['business_form'] ?? null,
                                    nature_of_business: $data['nature_of_business'] ?? null,
                                    year_connected: $data['year_connected'] ?? null,
                                    position: $data['position'] ?? null,
                                    employment_status: $data['employment_status'] ?? null,
                                );

                                $incomeVerificationData = new IncomeVerificationData(
                                    basic_salary: $data['basic_salary'] ?? null,
                                    allowances: $data['allowances'] ?? null,
                                    business_income: $data['business_income'] ?? null,
                                    other_income: $data['other_income'] ?? null,
                                    monthly_income: $data['monthly_income'] ?? null,
                                    annual_income: $data['annual_income'] ?? null,
                                );
                                $creditAndBackground = $this->record->credit_and_background()->updateOrCreate(['member_id' => $this->record->id], [
                                    'nickname' => $data['nickname'] ?? null,
                                    'nationality' => $data['nationality'] ?? null,
                                    'school' => $data['school'] ?? null,
                                    'spouse' => $spouseData,
                                    'children' => $childrenData,
                                    'employment_verification' => $employmentVerificationData,
                                    'income_verification' => $incomeVerificationData,
                                ]);
                                Notification::make()->title('Credit & background updated.')->success()->send();
                            }),
                    ])->alignRight(),
                ]);
        }
    }
