<?php

namespace App\Filament\App\Resources;

use App\Data\ChildData;
use App\Enums\MemberTypes;
use App\Filament\App\Resources\MemberResource\Pages\CbuAmortizationSchedule;
use App\Filament\App\Resources\MemberResource\Pages\CbuSubsidiaryLedger;
use App\Filament\App\Resources\MemberResource\Pages\CreateMember;
use App\Filament\App\Resources\MemberResource\Pages\EditMember;
use App\Filament\App\Resources\MemberResource\Pages\EditMemberLoan;
use App\Filament\App\Resources\MemberResource\Pages\ImprestSubsidiaryLedger;
use App\Filament\App\Resources\MemberResource\Pages\ListMembers;
use App\Filament\App\Resources\MemberResource\Pages\LoanAmortizationSchedule;
use App\Filament\App\Resources\MemberResource\Pages\LoanDisclosureSheet;
use App\Filament\App\Resources\MemberResource\Pages\LoanSubsidiaryLedger;
use App\Filament\App\Resources\MemberResource\Pages\LoveGiftsSubsidiaryLedger;
use App\Filament\App\Resources\MemberResource\Pages\MembersReport;
use App\Filament\App\Resources\MemberResource\Pages\PrintLoanHistory;
use App\Filament\App\Resources\MemberResource\Pages\PrintMemberProfile;
use App\Filament\App\Resources\MemberResource\Pages\SavingsSubsidiaryLedger;
use App\Filament\App\Resources\MemberResource\Pages\TimeDepositSubsidiaryLedger;
use App\Filament\App\Resources\MemberResource\Pages\ViewMember;
use App\Infolists\Components\BeneficiariesEntry;
use App\Infolists\Components\DependentsEntry;
use App\Livewire\App\CbuTable;
use App\Livewire\App\LoansTable;
use App\Livewire\App\MsoTable;
use App\Models\Member;
use App\Models\MembershipStatus;
use App\Models\MemberSubtype;
use App\Models\MemberType;
use App\Models\Occupation;
use App\Models\OfficersList;
use App\Models\Position;
use App\Models\Religion;
use App\Oxytoxin\Providers\OverrideProvider;
use App\Oxytoxin\Providers\ShareCapitalProvider;
use Auth;
use Carbon\Carbon;
use DB;
use Filament\Actions\Action;
use Filament\Actions\ActionGroup;
use Filament\Actions\DeleteAction;
use Filament\Actions\EditAction;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Hidden;
use Filament\Forms\Components\Repeater;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\SpatieMediaLibraryFileUpload;
use Filament\Forms\Components\TextInput;
use Filament\Infolists\Components\SpatieMediaLibraryImageEntry;
use Filament\Infolists\Components\TextEntry;
use Filament\Notifications\Notification;
use Filament\Resources\Resource;
use Filament\Schemas\Components\Actions;
use Filament\Schemas\Components\Fieldset;
use Filament\Schemas\Components\Grid;
use Filament\Schemas\Components\Livewire;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Components\Tabs;
use Filament\Schemas\Components\Tabs\Tab;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Enums\FiltersLayout;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;
use Malzariey\FilamentDaterangepickerFilter\Filters\DateRangeFilter;
use Throwable;

class MemberResource extends Resource
{
    protected static ?string $model = Member::class;

    protected static ?string $navigationLabel = 'Membership';

    protected static string|\UnitEnum|null $navigationGroup = 'Cashier';

    protected static ?int $navigationSort = 3;

    public static function shouldRegisterNavigation(): bool
    {
        return Auth::user()->roles()->count() > 1 || ! Auth::user()->hasRole('member');
    }

    public static function infolist(Schema $schema): Schema
    {
        $tabs = self::getInfoTabs();

        return $schema
            ->components([
                Tabs::make()
                    ->tabs($tabs)->persistTabInQueryString(),
            ])
            ->columns(1);
    }

    public static function getInfoTabs(): array
    {
        $tabs = [
            Tab::make('Profile')
                ->schema([
                    Section::make([
                        SpatieMediaLibraryImageEntry::make('profile_photo')
                            ->label('')
                            ->collection('profile_photo')->circular()
                            ->visible(fn ($record) => $record->getFirstMedia('profile_photo')),
                        TextEntry::make('full_name')->extraAttributes(['class' => 'font-semibold'])->inlineLabel()->alignStart(),
                        TextEntry::make('contact')->extraAttributes(['class' => 'font-semibold'])->inlineLabel()->alignStart(),
                        TextEntry::make('age')->extraAttributes(['class' => 'font-semibold'])->inlineLabel()->alignStart(),
                        TextEntry::make('dob')->label('Date of Birth')->date('F d, Y')->extraAttributes(['class' => 'font-semibold'])->inlineLabel(),
                        TextEntry::make('address')->label('Address')->extraAttributes(['class' => 'font-semibold'])->inlineLabel(),
                        TextEntry::make('place_of_birth')->label('Place of Birth')->extraAttributes(['class' => 'font-semibold'])->inlineLabel(),
                        TextEntry::make('gender.name')->extraAttributes(['class' => 'font-semibold'])->inlineLabel(),
                        TextEntry::make('credit_and_background.civil_status.name')->extraAttributes(['class' => 'font-semibold'])->inlineLabel(),
                        TextEntry::make('contact_number')->extraAttributes(['class' => 'font-semibold'])->inlineLabel(),
                        TextEntry::make('religion.name')->extraAttributes(['class' => 'font-semibold'])->inlineLabel(),
                        TextEntry::make('credit_and_background.highest_educational_attainment')->extraAttributes(['class' => 'font-semibold'])->inlineLabel(),
                        TextEntry::make('tin')->extraAttributes(['class' => 'font-semibold'])->inlineLabel()->label('TIN'),
                        TextEntry::make('member_type.name')->extraAttributes(['class' => 'font-semibold'])->inlineLabel()->label('Member Type'),
                        TextEntry::make('division.name')->extraAttributes(['class' => 'font-semibold'])->inlineLabel(),
                        TextEntry::make('grade')->extraAttributes(['class' => 'font-semibold'])->inlineLabel(),
                        TextEntry::make('section')->extraAttributes(['class' => 'font-semibold'])->inlineLabel(),
                    ]),
                    Section::make()
                        ->schema([
                            TextEntry::make('credit_and_background.occupation.name')->extraAttributes(['class' => 'font-semibold'])->inlineLabel(),
                            TextEntry::make('credit_and_background.occupation_description')->extraAttributes(['class' => 'font-semibold'])->inlineLabel(),
                            TextEntry::make('credit_and_background.annual_income')->money('PHP')->extraAttributes(['class' => 'font-semibold'])->inlineLabel(),
                            TextEntry::make('credit_and_background.monthly_salary')->money('PHP')->extraAttributes(['class' => 'font-semibold'])->inlineLabel(),
                            TextEntry::make('credit_and_background.other_income_sources')->extraAttributes(['class' => 'font-semibold'])->inlineLabel(),
                        ]),
                    Section::make()
                        ->schema([
                            DependentsEntry::make('dependents')->label('Number of Dependents')
                                ->inlineLabel(),
                        ]),
                    Section::make()
                        ->schema([
                            BeneficiariesEntry::make('beneficiaries')->label('Number of Beneficiaries')
                                ->inlineLabel(),
                        ]),
                    Section::make('Initial Capital Subscription')
                        ->schema([
                            TextEntry::make('membership_acceptance.effectivity_date')->label('Membership Date')->date('F d, Y')->extraAttributes(['class' => 'font-semibold'])->inlineLabel(),
                            TextEntry::make('membership_acceptance.bod_resolution')->label('BOD Resolution')->extraAttributes(['class' => 'font-semibold'])->inlineLabel(),
                            TextEntry::make('member_type.name')->label('Type of Member')->extraAttributes(['class' => 'font-semibold'])->inlineLabel(),
                            TextEntry::make('initial_capital_subscription.number_of_shares')->formatStateUsing(fn ($state) => round($state, 0))->label('# of Shares Subscribed')->extraAttributes(['class' => 'font-semibold'])->inlineLabel(),
                            TextEntry::make('initial_capital_subscription.amount_subscribed')->label('Amount Subscribed')->money('PHP')->extraAttributes(['class' => 'font-semibold'])->inlineLabel(),
                        ]),
                    Actions::make([
                        Action::make('print')
                            ->url(fn ($livewire) => route('filament.app.resources.members.print', ['member' => $livewire->record])),
                    ])->alignEnd(),
                ]),
        ];

        $tabs[] = Tab::make('CBU')
            ->schema(fn ($record) => [
                Livewire::make(CbuTable::class, ['member' => $record]),
            ]);
        $tabs[] = Tab::make('MSO')
            ->schema(fn ($record) => [
                Livewire::make(MsoTable::class, ['member' => $record]),
            ]);
        $tabs[] = Tab::make('Loan')
            ->schema(fn ($record) => [
                Actions::make([
                    Action::make('print_loan_history')
                        ->label('Print Loan History')
                        ->icon('heroicon-o-printer')
                        ->url(fn ($livewire) => route('filament.app.resources.members.print-loan-history', ['member' => $livewire->record])),
                ])->alignEnd(),
                Livewire::make(LoansTable::class, ['member' => $record]),
            ]);

        return $tabs;
    }

    public static function form(Schema $schema): Schema
    {
        return $schema
            ->components([
                Grid::make(2)
                    ->schema([
                        Section::make()
                            ->schema([
                                SpatieMediaLibraryFileUpload::make('profile_photo')
                                    ->avatar()
                                    ->alignCenter()
                                    ->collection('profile_photo')
                                    ->columnSpanFull(),
                                Fieldset::make('Member Information')
                                    ->columns(1)
                                    ->schema([
                                        TextInput::make('first_name')
                                            ->label('First Name')
                                            ->dehydrateStateUsing(fn ($state) => strtoupper($state))
                                            ->required()
                                            ->maxLength(125),
                                        TextInput::make('last_name')
                                            ->label('Last Name')
                                            ->dehydrateStateUsing(fn ($state) => strtoupper($state))
                                            ->required()
                                            ->maxLength(125),
                                        TextInput::make('middle_initial')
                                            ->label('MI')
                                            ->dehydrateStateUsing(fn ($state) => $state ? strtoupper($state) : null)
                                            ->maxLength(1),
                                    ]),
                            ])
                            ->columnSpan(1),
                        Section::make()
                            ->schema([
                                Fieldset::make('Officership')
                                    ->schema([
                                        Select::make('officers_list_id')
                                            ->label('Year')
                                            ->options(OfficersList::pluck('year', 'id')),
                                        Select::make('position_id')
                                            ->label('Position')
                                            ->options(Position::pluck('name', 'id')),
                                    ]),
                                Select::make('member_type_id')
                                    ->label('Member Type')
                                    ->relationship('member_type', 'name')
                                    ->live()
                                    ->afterStateUpdated(function ($state, $set) {
                                        $member_type = MemberType::find($state);
                                        $set('number_of_shares', round($member_type->default_number_of_shares, 0));
                                        $set('amount_subscribed', round($member_type->default_amount_subscribed, 0));
                                        if ($member_type?->id == MemberTypes::REGULAR->value) {
                                            $set('credit_and_background.present_employer', 'SKSU-Sultan Kudarat State University');

                                            return;
                                        }
                                        if ($member_type?->id == MemberTypes::LABORATORY->value) {
                                            $set('credit_and_background.dependents', [
                                                [
                                                    'relationship' => 'FATHER',
                                                ],
                                                [
                                                    'relationship' => 'MOTHER',
                                                ],
                                            ]);
                                        }
                                        $set('member_subtype_id', null);
                                        $set('credit_and_background.present_employer', '');
                                    })
                                    ->required(),
                                Select::make('member_subtype_id')
                                    ->label('Member Subtype')
                                    ->relationship('member_subtype', 'name')
                                    ->options(fn ($get) => MemberSubtype::whereMemberTypeId($get('member_type_id'))->pluck('name', 'id'))
                                    ->required(fn ($get) => $get('member_type_id') == MemberTypes::REGULAR->value)
                                    ->visible(fn ($get) => $get('member_type_id') == MemberTypes::REGULAR->value)
                                    ->live(),
                                Select::make('division_id')
                                    ->label('Division')
                                    ->visible(fn ($get) => $get('member_type_id') != MemberTypes::LABORATORY->value)
                                    ->relationship('division', 'name'),
                                TextInput::make('grade')
                                    ->visible(fn ($get) => $get('member_type_id') == MemberTypes::LABORATORY->value),
                                TextInput::make('section')
                                    ->visible(fn ($get) => $get('member_type_id') == MemberTypes::LABORATORY->value),
                                Select::make('patronage_status_id')
                                    ->label('Patronage Status')
                                    ->default(1)
                                    ->relationship('patronage_status', 'name')->required(),
                                TextInput::make('tin')
                                    ->label('TIN')
                                    ->maxLength(30),
                            ])
                            ->columnSpan(1),
                    ]),
                Grid::make(3)
                    ->schema([
                        TextInput::make('contact'),
                        DatePicker::make('dob')
                            ->before(today()->subYearsNoOverflow(10))
                            ->validationAttribute('Date of Birth')
                            ->live(onBlur: true)
                            ->afterStateUpdated(fn ($set, $state) => $set('age', Carbon::make($state)?->diffInYears(today())))
                            ->label('Date of Birth'),
                        TextInput::make('age')->readOnly()->dehydrated(false)->formatStateUsing(fn ($record) => $record?->age),
                        TextInput::make('place_of_birth')
                            ->label('Place of Birth'),
                        Section::make('Address')
                            ->schema([
                                TextInput::make('address')->columnSpanFull(),
                                Select::make('region_id')
                                    ->label('Region')
                                    ->live()
                                    ->relationship('region', 'description'),
                                Select::make('province_id')
                                    ->label('Province')
                                    ->live()
                                    ->disabled(fn ($get) => ! $get('region_id'))
                                    ->relationship('province', 'name', fn ($query, $get) => $query->whereRegionId($get('region_id'))),
                                Select::make('municipality_id')
                                    ->label('Municipality')
                                    ->live()
                                    ->disabled(fn ($get) => ! $get('province_id'))
                                    ->relationship('municipality', 'name', fn ($query, $get) => $query->whereProvinceId($get('province_id'))),
                                Select::make('barangay_id')
                                    ->label('Barangay')
                                    ->disabled(fn ($get) => ! $get('municipality_id'))
                                    ->relationship('barangay', 'name', fn ($query, $get) => $query->whereMunicipalityId($get('municipality_id'))),
                            ])->columns(2),
                    ]),
                Grid::make(2)
                    ->schema([
                        Select::make('gender_id')
                            ->label('Gender')
                            ->relationship('gender', 'name'),
                        Select::make('religion_id')
                            ->label('Religion')
                            ->relationship('religion', 'name')
                            ->options(Religion::pluck('name', 'id')),
                    ]),
                Repeater::make('beneficiaries')
                    ->default([])
                    ->label('Beneficiaries')
                    ->table([
                        Repeater\TableColumn::make('Name'),
                        Repeater\TableColumn::make('Date of Birth'),
                        Repeater\TableColumn::make('Relationship'),
                    ])
                    ->schema([
                        TextInput::make('name')->required(),
                        DatePicker::make('dob')->format('Y-m-d'),
                        Select::make('relationship')
                            ->options([
                                'FATHER' => 'FATHER',
                                'MOTHER' => 'MOTHER',
                                'HUSBAND' => 'HUSBAND',
                                'WIFE' => 'WIFE',
                                'SON' => 'SON',
                                'DAUGHTER' => 'DAUGHTER',
                                'BROTHER' => 'BROTHER',
                                'SISTER' => 'SISTER',
                                'COUSIN' => 'COUSIN',
                                'OTHERS' => 'OTHERS',
                            ])->required(),
                    ])
                    ->columnSpanFull(),
                Section::make('Membership Acceptance')
                    ->schema([
                        TextInput::make('bod_resolution')->label('BOD Resolution'),
                        DatePicker::make('effectivity_date')->native(false)->label('Membership Date')->required()->default(fn ($livewire) => $livewire->transaction_date),
                        Hidden::make('type')->default(MembershipStatus::ACCEPTANCE),
                    ])->relationship('membership_acceptance'),
                Section::make('Initial Capital Subscription')
                    ->hiddenOn('edit')
                    ->visible(fn ($get) => $get('member_type_id'))
                    ->schema([
                        TextInput::make('number_of_terms')->readOnly()->minValue(0)->default(12),
                        TextInput::make('number_of_shares')->minValue(0)->default(0)
                            ->live(true)
                            ->afterStateUpdated(function ($set, $state, $get) {
                                $memberType = MemberType::find($get('member_type_id'));
                                $data = ShareCapitalProvider::fromNumberOfShares($state, $memberType->initial_number_of_terms, $memberType->par_value);
                                $set('amount_subscribed', $data['amount_subscribed']);
                            }),
                        TextInput::make('amount_subscribed')
                            ->moneymask()->default(0)
                            ->afterStateUpdated(function ($set, $state, $get) {
                                $memberType = MemberType::find($get('member_type_id'));
                                $data = ShareCapitalProvider::fromAmountSubscribed($state, $memberType->initial_number_of_terms, $memberType->par_value);
                                $set('number_of_shares', $data['number_of_shares']);
                            }),
                    ]),
                Section::make('Credit & Background Investigation')
                    ->hiddenOn('create')
                    ->statePath('credit_and_background')
                    ->schema(static::getCreditAndBackgroundFormSchema()),
            ]);
    }

    public static function getCreditAndBackgroundFormSchema(): array
    {
        return [
            Grid::make(3)
                ->schema([
                    TextInput::make('nickname')->maxLength(125),
                    TextInput::make('nationality')->maxLength(125),
                    TextInput::make('school')->maxLength(125),
                ]),
            Section::make('Personal Information')
                ->schema([
                    Grid::make(3)
                        ->schema([
                            Select::make('civil_status_id')
                                ->label('Civil Status')
                                ->options(\App\Models\CivilStatus::pluck('name', 'id'))
                                ->default(1),
                            Select::make('occupation_id')
                                ->label('Occupation')
                                ->options(Occupation::pluck('name', 'id')),
                            TextInput::make('occupation_description'),
                            TextInput::make('highest_educational_attainment')
                                ->maxLength(125),
                            TextInput::make('present_employer'),
                            TextInput::make('other_income_sources'),
                        ]),
                    Grid::make(3)
                        ->schema([
                            TextInput::make('annual_income')
                                ->moneymask()
                                ->minValue(0),
                            TextInput::make('monthly_salary')
                                ->moneymask()
                                ->minValue(0),
                        ]),
                    Repeater::make('dependents')
                        ->default([])
                        ->label(fn ($get) => $get('') == 4 ? 'Parents' : 'Dependents')
                        ->table([
                            Repeater\TableColumn::make('Name'),
                            Repeater\TableColumn::make('Date of Birth'),
                            Repeater\TableColumn::make('Relationship'),
                        ])
                        ->schema([
                            TextInput::make('name')->required(),
                            DatePicker::make('dob')->format('Y-m-d'),
                            Select::make('relationship')
                                ->options([
                                    'FATHER' => 'FATHER',
                                    'MOTHER' => 'MOTHER',
                                    'HUSBAND' => 'HUSBAND',
                                    'WIFE' => 'WIFE',
                                    'SON' => 'SON',
                                    'DAUGHTER' => 'DAUGHTER',
                                    'BROTHER' => 'BROTHER',
                                    'SISTER' => 'SISTER',
                                    'COUSIN' => 'COUSIN',
                                    'OTHERS' => 'OTHERS',
                                ])->required(),
                        ])
                        ->columnSpanFull(),
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
                        ->itemLabel(fn (array $state): ?string => $state['name'] ?? null),
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
        ];
    }

    public static function getCreditAndBackgroundFormData(?Member $member): array
    {
        $cibi = $member?->credit_and_background;

        return array_filter([
            'nickname' => $cibi?->nickname,
            'nationality' => $cibi?->nationality,
            'school' => $cibi?->school,
            'civil_status_id' => $cibi?->civil_status_id,
            'occupation_id' => $cibi?->occupation_id,
            'occupation_description' => $cibi?->occupation_description,
            'present_employer' => $cibi?->present_employer,
            'highest_educational_attainment' => $cibi?->highest_educational_attainment,
            'annual_income' => $cibi?->annual_income,
            'monthly_salary' => $cibi?->monthly_salary,
            'other_income_sources' => $cibi?->other_income_sources,
            'dependents' => $cibi?->dependents ?? [],
            'spouse_name' => $cibi?->spouse_name,
            'spouse_nickname' => $cibi?->spouse_nickname,
            'spouse_middle_name' => $cibi?->spouse_middle_name,
            'spouse_date_of_birth' => $cibi?->spouse_date_of_birth,
            'spouse_age' => $cibi?->spouse_age,
            'spouse_contact_number' => $cibi?->spouse_contact_number,
            'spouse_civil_status' => $cibi?->spouse_civil_status,
            'spouse_nationality' => $cibi?->spouse_nationality,
            'spouse_address' => $cibi?->spouse_address,
            'spouse_highest_educational_attainment' => $cibi?->spouse_highest_educational_attainment,
            'spouse_school' => $cibi?->spouse_school,
            'children' => collect($cibi?->children ?? [])->map(fn (ChildData $child): array => $child->toArray())->toArray(),
            'employer' => $cibi?->employer,
            'office_address' => $cibi?->office_address,
            'business_form' => $cibi?->business_form,
            'nature_of_business' => $cibi?->nature_of_business,
            'year_connected' => $cibi?->year_connected,
            'position' => $cibi?->position,
            'employment_status' => $cibi?->employment_status,
            'basic_salary' => $cibi?->basic_salary,
            'allowances' => $cibi?->allowances,
            'business_income' => $cibi?->business_income,
            'other_income' => $cibi?->other_income,
            'monthly_income' => $cibi?->monthly_income,
        ], fn (mixed $value): bool => $value !== null && $value !== []);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->modifyQueryUsing(function ($query) {
                $query->whereNot('member_type_id', MemberTypes::ORGANIZATION->value);
            })
            ->columns([
                TextColumn::make('id'),
                TextColumn::make('mpc_code')
                    ->label('Code')
                    ->sortable()
                    ->searchable(),
                TextColumn::make('first_name')
                    ->sortable()
                    ->searchable(),
                TextColumn::make('last_name')
                    ->sortable()
                    ->searchable(),
                TextColumn::make('middle_initial')
                    ->label('MI')
                    ->alignCenter(),
                TextColumn::make('division.name')
                    ->sortable(),
                TextColumn::make('dob')
                    ->label('Date of Birth')
                    ->date('F d, Y')
                    ->sortable(),
                TextColumn::make('age')
                    ->numeric()
                    ->alignCenter(),
                TextColumn::make('credit_and_background.civil_status.name')
                    ->alignCenter(),
                TextColumn::make('gender.name')
                    ->sortable()
                    ->alignCenter(),
                TextColumn::make('tin')
                    ->label('TIN')
                    ->alignCenter()
                    ->searchable(),
                TextColumn::make('member_type.name')
                    ->sortable(),
                TextColumn::make('member_subtype.name')
                    ->sortable(),
                TextColumn::make('terminated_at')
                    ->date('m/d/Y')
                    ->sortable(),
                TextColumn::make('patronage_status.name'),
                TextColumn::make('membership_date')
                    ->label('Membership Date')
                    ->date('F d, Y'),
            ])
            ->filters([
                SelectFilter::make('member_type')
                    ->relationship('member_type', 'name'),
                SelectFilter::make('member_subtype')
                    ->relationship('member_subtype', 'name'),
                SelectFilter::make('gender')
                    ->relationship('gender', 'name'),
                SelectFilter::make('division')
                    ->relationship('division', 'name'),
                SelectFilter::make('patronage_status')
                    ->relationship('patronage_status', 'name'),
                SelectFilter::make('status')
                    ->options([
                        1 => 'Active',
                        2 => 'Terminated',
                    ])
                    ->default(1)
                    ->query(
                        fn ($query, $state) => $query
                            ->when($state['value'] == 1, fn ($q) => $q->whereNull('terminated_at'))
                            ->when($state['value'] == 2, fn ($q) => $q->whereNotNull('terminated_at'))
                    ),
                SelectFilter::make('credit_and_background.civil_status')
                    ->relationship('credit_and_background.civil_status', 'name')
                    ->searchable()
                    ->preload(),
                DateRangeFilter::make('membership_date')
                    ->format('m/d/Y')
                    ->displayFormat('MM/DD/YYYY')
                    ->label('Date of Membership'),
            ])
            ->filtersLayout(FiltersLayout::AboveContent)
            ->persistFiltersInSession()
            ->recordActions([
                ActionGroup::make([
                    EditAction::make()
                        ->hidden(fn ($record) => $record->terminated_at)
                        ->visible(Auth::user()->can('manage members')),
                    DeleteAction::make()
                        ->hidden(fn ($record) => $record->terminated_at)
                        ->schema([
                            TextInput::make('passkey')
                                ->hint("Manager's Password")
                                ->required()
                                ->password(),
                        ])
                        ->action(function (Member $record, $data) {
                            if (! OverrideProvider::promptManagerPasskey($data['passkey'])) {
                                return false;
                            }
                            DB::beginTransaction();
                            try {
                                if ($record->capital_subscription_payments()->count() <= 1) {
                                    $record->capital_subscriptions()->delete();
                                    $record->delete();
                                } else {
                                    return Notification::make()->title('Member has existing payments.')->danger()->send();
                                }
                            } catch (Throwable $th) {
                                DB::rollBack();

                                return Notification::make()->title('Member has existing payments.')->danger()->send();
                            }
                            Notification::make()->title('Member deleted.')->success()->send();
                            DB::commit();

                            return true;
                        })->visible(Auth::user()->can('manage members')),
                    Action::make('terminate')
                        ->requiresConfirmation()
                        ->visible(Auth::user()->can('manage members'))
                        ->hidden(fn ($record) => $record->terminated_at)
                        ->schema([
                            TextInput::make('bod_resolution')->label('BOD Resolution')->required(),
                            DatePicker::make('termination_date')->default(config('app.transaction_date') ?? today())->native(false)->required(),
                            TextInput::make('termination_voucher_number')->label('Reference (JEV/DV No.)')->required(),
                            TextInput::make('capital_amount_closed')->label('Capital Amount Closed')->numeric()->prefix('₱')
                                ->default(fn ($record) => $record->capital_subscription_payments()->sum('amount'))
                                ->required(),
                        ])
                        ->icon(Heroicon::NoSymbol)
                        ->action(function ($record, $data) {
                            DB::beginTransaction();
                            MembershipStatus::create([
                                'member_id' => $record->id,
                                'type' => MembershipStatus::TERMINATION,
                                'bod_resolution' => $data['bod_resolution'],
                                'effectivity_date' => $data['termination_date'],
                                'termination_voucher_number' => $data['termination_voucher_number'],
                                'capital_amount_closed' => $data['capital_amount_closed'],
                            ]);
                            $record->update(['terminated_at' => config('app.transaction_date') ?? now()]);
                            DB::commit();
                            Notification::make()->title('Member terminated.')->success()->send();
                        }),
                ]),
            ])
            ->toolbarActions([])
            ->emptyStateActions([])
            ->recordUrl(fn (Member $record) => MemberResource::getUrl('view', ['record' => $record]))
            ->paginated([10, 25, 50]);
    }

    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => ListMembers::route('/'),
            'create' => CreateMember::route('/create'),
            'report' => MembersReport::route('/reports'),
            'view' => ViewMember::route('/{record}'),
            'print' => PrintMemberProfile::route('/{member}/print'),
            'print-loan-history' => PrintLoanHistory::route('/{member}/print-loan-history'),
            'edit' => EditMember::route('/{record}/edit'),
            'loan.edit' => EditMemberLoan::route('/{record}/{loan}/edit'),
            'cbu-subsidiary-ledger' => CbuSubsidiaryLedger::route('cbu-subsidiary-ledger/{member}'),
            'cbu-amortization-schedule' => CbuAmortizationSchedule::route('cbu-amortization-schedule/{cbu}'),
            'savings-subsidiary-ledger' => SavingsSubsidiaryLedger::route('savings-subsidiary-ledger/{savings_account}'),
            'imprest-subsidiary-ledger' => ImprestSubsidiaryLedger::route('imprest-subsidiary-ledger/{member}'),
            'love-gifts-subsidiary-ledger' => LoveGiftsSubsidiaryLedger::route('love-gifts-subsidiary-ledger/{member}'),
            'loan-subsidiary-ledger' => LoanSubsidiaryLedger::route('loan-subsidiary-ledger/{loan}'),
            'loan-amortization-schedule' => LoanAmortizationSchedule::route('loan-amortization-schedule/{loan}'),
            'loan-disclosure-sheet' => LoanDisclosureSheet::route('loan-disclosure-sheet/{loan}'),
            'time-deposit-subsidiary-ledger' => TimeDepositSubsidiaryLedger::route('time-deposit-subsidiary-ledger/{time_deposit_account}'),
        ];
    }
}
