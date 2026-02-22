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
            $data['nickname'] = $this->record->credit_and_background?->nickname ?? null;
            $data['nationality'] = $this->record->credit_and_background?->nationality ?? null;
            $data['school'] = $this->record->credit_and_background?->school ?? null;

            $data['spouse_name'] = $this->record->credit_and_background?->spouse?->name ?? null;
            $data['spouse_nickname'] = $this->record->credit_and_background?->spouse?->nickname ?? null;
            $data['spouse_middle_name'] = $this->record->credit_and_background?->spouse?->middle_name ?? null;
            $data['spouse_date_of_birth'] = $this->record->credit_and_background?->spouse?->date_of_birth ?? null;
            $data['spouse_age'] = $this->record->credit_and_background?->spouse?->age ?? null;
            $data['spouse_contact_number'] = $this->record->credit_and_background?->spouse?->contact_number ?? null;
            $data['spouse_civil_status'] = $this->record->credit_and_background?->spouse?->civil_status ?? null;
            $data['spouse_nationality'] = $this->record->credit_and_background?->spouse?->nationality ?? null;
            $data['spouse_address'] = $this->record->credit_and_background?->spouse?->address ?? null;
            $data['spouse_highest_educational_attainment'] = $this->record->credit_and_background?->spouse?->highest_educational_attainment ?? null;
            $data['spouse_school'] = $this->record->credit_and_background?->spouse?->school ?? null;

            $data['children'] = collect($this->record->credit_and_background?->children ?? [])
                ->map(fn(ChildData $child) => [
                    'name' => $child->name,
                    'birthdate' => $child->birthdate,
                    'course_and_school' => $child->course_and_school,
                ])
                ->toArray();

            $data['employer'] = $this->record->credit_and_background?->employment_verification?->employer ?? null;
            $data['office_address'] = $this->record->credit_and_background?->employment_verification?->office_address ?? null;
            $data['business_form'] = $this->record->credit_and_background?->employment_verification?->business_form ?? null;
            $data['nature_of_business'] = $this->record->credit_and_background?->employment_verification?->nature_of_business ?? null;
            $data['year_connected'] = $this->record->credit_and_background?->employment_verification?->year_connected ?? null;
            $data['position'] = $this->record->credit_and_background?->employment_verification?->position ?? null;
            $data['employment_status'] = $this->record->credit_and_background?->employment_verification?->employment_status ?? null;

            $data['basic_salary'] = $this->record->credit_and_background?->income_verification?->basic_salary ?? null;
            $data['allowances'] = $this->record->credit_and_background?->income_verification?->allowances ?? null;
            $data['business_income'] = $this->record->credit_and_background?->income_verification?->business_income ?? null;
            $data['other_income'] = $this->record->credit_and_background?->income_verification?->other_income ?? null;
            $data['monthly_income'] = $this->record->credit_and_background?->income_verification?->monthly_income ?? null;
            $data['annual_income'] = $this->record->credit_and_background?->income_verification?->annual_income ?? null;

            $this->form->fill($data);
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
                            ->action(function () {
                                $spouseData = new SpouseData(
                                    name: $this->data['spouse_name'] ?? null,
                                    nickname: $this->data['spouse_nickname'] ?? null,
                                    middle_name: $this->data['spouse_middle_name'] ?? null,
                                    date_of_birth: $this->data['spouse_date_of_birth'] ?? null,
                                    age: $this->data['spouse_age'] ?? null,
                                    contact_number: $this->data['spouse_contact_number'] ?? null,
                                    civil_status: $this->data['spouse_civil_status'] ?? null,
                                    nationality: $this->data['spouse_nationality'] ?? null,
                                    address: $this->data['spouse_address'] ?? null,
                                    highest_educational_attainment: $this->data['spouse_highest_educational_attainment'] ?? null,
                                    school: $this->data['spouse_school'] ?? null,
                                );

                                $childrenData = collect($this->data['children'] ?? [])
                                    ->map(fn($child) => new ChildData(
                                        name: $child['name'] ?? null,
                                        birthdate: $child['birthdate'] ?? null,
                                        course_and_school: $child['course_and_school'] ?? null,
                                    ))
                                    ->toArray();

                                $employmentVerificationData = new EmploymentVerificationData(
                                    employer: $this->data['employer'] ?? null,
                                    office_address: $this->data['office_address'] ?? null,
                                    business_form: $this->data['business_form'] ?? null,
                                    nature_of_business: $this->data['nature_of_business'] ?? null,
                                    year_connected: $this->data['year_connected'] ?? null,
                                    position: $this->data['position'] ?? null,
                                    employment_status: $this->data['employment_status'] ?? null,
                                );

                                $incomeVerificationData = new IncomeVerificationData(
                                    basic_salary: $this->data['basic_salary'] ?? null,
                                    allowances: $this->data['allowances'] ?? null,
                                    business_income: $this->data['business_income'] ?? null,
                                    other_income: $this->data['other_income'] ?? null,
                                    monthly_income: $this->data['monthly_income'] ?? null,
                                    annual_income: $this->data['annual_income'] ?? null,
                                );

                                $creditAndBackground = $this->record->credit_and_background()->firstOrNew();

                                $creditAndBackground->fill([
                                    'nickname' => $this->data['nickname'] ?? null,
                                    'nationality' => $this->data['nationality'] ?? null,
                                    'school' => $this->data['school'] ?? null,
                                    'spouse' => $spouseData,
                                    'children' => $childrenData,
                                    'employment_verification' => $employmentVerificationData,
                                    'income_verification' => $incomeVerificationData,
                                ])->save();

                                Notification::make()->title('Credit & background updated.')->success()->send();
                            }),
                    ])->alignRight(),
                ]);
        }
    }
