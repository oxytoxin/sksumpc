<?php

namespace App\Filament\App\Resources;

use App\Enums\MemberTypes;
use App\Filament\App\Resources\MemberResource\Pages;
use App\Filament\App\Resources\MemberResource\Pages\CbuAmortizationSchedule;
use App\Filament\App\Resources\MemberResource\Pages\CbuSubsidiaryLedger;
use App\Filament\App\Resources\MemberResource\Pages\ImprestSubsidiaryLedger;
use App\Filament\App\Resources\MemberResource\Pages\LoanAmortizationSchedule;
use App\Filament\App\Resources\MemberResource\Pages\LoanDisclosureSheet;
use App\Filament\App\Resources\MemberResource\Pages\LoanSubsidiaryLedger;
use App\Filament\App\Resources\MemberResource\Pages\LoveGiftsSubsidiaryLedger;
use App\Filament\App\Resources\MemberResource\Pages\SavingsSubsidiaryLedger;
use App\Filament\App\Resources\MemberResource\Pages\TimeDepositSubsidiaryLedger;
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
use Awcodes\FilamentTableRepeater\Components\TableRepeater;
use Carbon\Carbon;
use DB;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Fieldset;
use Filament\Forms\Components\Grid;
use Filament\Forms\Components\Hidden;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\SpatieMediaLibraryFileUpload;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Form;
use Filament\Infolists\Components\Actions;
use Filament\Infolists\Components\Actions\Action;
use Filament\Infolists\Components\Livewire;
use Filament\Infolists\Components\Section as InfolistSection;
use Filament\Infolists\Components\SpatieMediaLibraryImageEntry;
use Filament\Infolists\Components\Tabs;
use Filament\Infolists\Components\Tabs\Tab;
use Filament\Infolists\Components\TextEntry;
use Filament\Infolists\Infolist;
use Filament\Notifications\Notification;
use Filament\Resources\Resource;
use Filament\Support\Colors\Color;
use Filament\Tables;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Contracts\HasTable;
use Filament\Tables\Enums\FiltersLayout;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;
use Malzariey\FilamentDaterangepickerFilter\Filters\DateRangeFilter;

class MemberResource extends Resource
{
    protected static ?string $model = Member::class;

    protected static ?string $navigationLabel = 'Membership';

    protected static ?string $navigationGroup = 'Cashier';

    protected static ?int $navigationSort = 3;

    public static function shouldRegisterNavigation(): bool
    {
        return Auth::user()->roles()->count() > 1 || ! Auth::user()->hasRole('member');
    }

    public static function infolist(Infolist $infolist): Infolist
    {
        $tabs = self::getInfoTabs();

        return $infolist
            ->schema([
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
                    InfolistSection::make([
                        SpatieMediaLibraryImageEntry::make('profile_photo')
                            ->label('')
                            ->collection('profile_photo')->circular()
                            ->visible(fn($record) => $record->getFirstMedia('profile_photo')),
                        TextEntry::make('full_name')->extraAttributes(['class' => 'font-semibold'])->inlineLabel()->alignStart(),
                        TextEntry::make('contact')->extraAttributes(['class' => 'font-semibold'])->inlineLabel()->alignStart(),
                        TextEntry::make('age')->extraAttributes(['class' => 'font-semibold'])->inlineLabel()->alignStart(),
                        TextEntry::make('dob')->label('Date of Birth')->date('F d, Y')->extraAttributes(['class' => 'font-semibold'])->inlineLabel(),
                        TextEntry::make('address')->label('Address')->extraAttributes(['class' => 'font-semibold'])->inlineLabel(),
                        TextEntry::make('place_of_birth')->label('Place of Birth')->extraAttributes(['class' => 'font-semibold'])->inlineLabel(),
                        TextEntry::make('gender.name')->extraAttributes(['class' => 'font-semibold'])->inlineLabel(),
                        TextEntry::make('civil_status.name')->extraAttributes(['class' => 'font-semibold'])->inlineLabel(),
                        TextEntry::make('contact_number')->extraAttributes(['class' => 'font-semibold'])->inlineLabel(),
                        TextEntry::make('religion.name')->extraAttributes(['class' => 'font-semibold'])->inlineLabel(),
                        TextEntry::make('highest_educational_attainment')->extraAttributes(['class' => 'font-semibold'])->inlineLabel(),
                        TextEntry::make('tin')->extraAttributes(['class' => 'font-semibold'])->inlineLabel()->label('TIN'),
                        TextEntry::make('member_type.name')->extraAttributes(['class' => 'font-semibold'])->inlineLabel()->label('Member Type'),
                        TextEntry::make('division.name')->extraAttributes(['class' => 'font-semibold'])->inlineLabel(),
                        TextEntry::make('grade')->extraAttributes(['class' => 'font-semibold'])->inlineLabel(),
                        TextEntry::make('section')->extraAttributes(['class' => 'font-semibold'])->inlineLabel(),
                    ]),
                    InfolistSection::make()
                        ->schema([
                            TextEntry::make('occupation.name')->extraAttributes(['class' => 'font-semibold'])->inlineLabel(),
                            TextEntry::make('occupation_description')->extraAttributes(['class' => 'font-semibold'])->inlineLabel(),
                            TextEntry::make('annual_income')->money('PHP')->extraAttributes(['class' => 'font-semibold'])->inlineLabel(),
                            TextEntry::make('other_income_sources')->extraAttributes(['class' => 'font-semibold'])->inlineLabel(),
                        ]),
                    InfolistSection::make()
                        ->schema([
                            DependentsEntry::make('dependents')->label('Number of Dependents')
                                ->inlineLabel(),
                        ]),
                    InfolistSection::make('Initial Capital Subscription')
                        ->schema([
                            TextEntry::make('membership_acceptance.effectivity_date')->label('Membership Date')->date('F d, Y')->extraAttributes(['class' => 'font-semibold'])->inlineLabel(),
                            TextEntry::make('membership_acceptance.bod_resolution')->label('BOD Resolution')->extraAttributes(['class' => 'font-semibold'])->inlineLabel(),
                            TextEntry::make('member_type.name')->label('Type of Member')->extraAttributes(['class' => 'font-semibold'])->inlineLabel(),
                            TextEntry::make('initial_capital_subscription.number_of_shares')->formatStateUsing(fn($state) => round($state, 0))->label('# of Shares Subscribed')->extraAttributes(['class' => 'font-semibold'])->inlineLabel(),
                            TextEntry::make('initial_capital_subscription.amount_subscribed')->label('Amount Subscribed')->money('PHP')->extraAttributes(['class' => 'font-semibold'])->inlineLabel(),
                        ]),
                    Actions::make([
                        Action::make('print')
                            ->url(fn($livewire) => route('filament.app.resources.members.print', ['member' => $livewire->record])),
                    ])->alignEnd(),
                ]),
        ];

        $tabs[] = Tab::make('CBU')
            ->schema(fn($record) => [
                Livewire::make(CbuTable::class, ['member' => $record]),
            ]);
        $tabs[] = Tab::make('MSO')
            ->schema(fn($record) => [
                Livewire::make(MsoTable::class, ['member' => $record]),
            ]);
        $tabs[] = Tab::make('Loan')
            ->schema(fn($record) => [
                Livewire::make(LoansTable::class, ['member' => $record]),
            ]);

        return $tabs;
    }

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Grid::make(2)
                    ->schema([
                        Section::make()
                            ->schema([
                                SpatieMediaLibraryFileUpload::make('profile_photo')
                                    ->avatar()
                                    ->alignCenter()
                                    ->collection('profile_photo'),
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
                                    ->relationship('member_type', 'name')
                                    ->live()
                                    ->afterStateUpdated(function ($state, $set) {
                                        $member_type = MemberType::find($state);
                                        $set('number_of_shares', round($member_type->default_number_of_shares, 0));
                                        $set('amount_subscribed', round($member_type->default_amount_subscribed, 0));
                                        if ($member_type?->id == MemberTypes::REGULAR->value) {
                                            $set('present_employer', 'SKSU-Sultan Kudarat State University');

                                            return;
                                        }
                                        if ($member_type?->id == MemberTypes::LABORATORY->value) {
                                            $set('dependents', [
                                                [
                                                    'relationship' => 'FATHER',
                                                ],
                                                [
                                                    'relationship' => 'MOTHER',
                                                ],
                                            ]);
                                        }
                                        $set('member_subtype_id', null);
                                        $set('present_employer', '');
                                    })
                                    ->required(),
                                Select::make('member_subtype_id')
                                    ->relationship('member_subtype', 'name')
                                    ->options(fn($get) => MemberSubtype::whereMemberTypeId($get('member_type_id'))->pluck('name', 'id'))
                                    ->required(fn($get) => $get('member_type_id') == MemberTypes::REGULAR->value)
                                    ->visible(fn($get) => $get('member_type_id') == MemberTypes::REGULAR->value)
                                    ->live(),
                                Select::make('division_id')
                                    ->visible(fn($get) => $get('member_type_id') != MemberTypes::LABORATORY->value)
                                    ->relationship('division', 'name'),
                                TextInput::make('grade')
                                    ->visible(fn($get) => $get('member_type_id') == MemberTypes::LABORATORY->value),
                                TextInput::make('section')
                                    ->visible(fn($get) => $get('member_type_id') == MemberTypes::LABORATORY->value),
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
                        TextInput::make('first_name')
                            ->label('First Name')
                            ->dehydrateStateUsing(fn($state) => strtoupper($state))
                            ->required()
                            ->maxLength(125),
                        TextInput::make('last_name')
                            ->label('Last Name')
                            ->dehydrateStateUsing(fn($state) => strtoupper($state))
                            ->required()
                            ->maxLength(125),
                        TextInput::make('middle_initial')
                            ->label('MI')
                            ->dehydrateStateUsing(fn($state) => $state ? strtoupper($state) : null)
                            ->maxLength(1),
                        TextInput::make('contact'),
                        DatePicker::make('dob')
                            ->before(today()->subYearsNoOverflow(10))
                            ->validationAttribute('Date of Birth')
                            ->live(onBlur: true)
                            ->afterStateUpdated(fn($set, $state) => $set('age', Carbon::make($state)?->diffInYears(today())))
                            ->label('Date of Birth'),
                        TextInput::make('age')->readOnly()->dehydrated(false)->formatStateUsing(fn($record) => $record?->age),
                        TextInput::make('place_of_birth')
                            ->label('Place of Birth'),
                        Section::make('Address')
                            ->schema([
                                TextInput::make('address')->columnSpanFull(),
                                Select::make('region_id')
                                    ->live()
                                    ->relationship('region', 'description'),
                                Select::make('province_id')
                                    ->live()
                                    ->disabled(fn($get) => ! $get('region_id'))
                                    ->relationship('province', 'name', fn($query, $get) => $query->whereRegionId($get('region_id'))),
                                Select::make('municipality_id')
                                    ->live()
                                    ->disabled(fn($get) => ! $get('province_id'))
                                    ->relationship('municipality', 'name', fn($query, $get) => $query->whereProvinceId($get('province_id'))),
                                Select::make('barangay_id')
                                    ->disabled(fn($get) => ! $get('municipality_id'))
                                    ->relationship('barangay', 'name', fn($query, $get) => $query->whereMunicipalityId($get('municipality_id'))),
                            ])->columns(2),
                    ]),
                Grid::make(3)
                    ->schema([
                        Select::make('gender_id')
                            ->relationship('gender', 'name'),
                        Select::make('civil_status_id')
                            ->relationship('civil_status', 'name')
                            ->default(1),
                        Select::make('religion_id')
                            ->relationship('religion', 'name')
                            ->options(Religion::pluck('name', 'id')),
                    ]),
                TableRepeater::make('dependents')
                    ->default([])
                    ->label(fn($get) => $get('member_type_id') == 4 ? 'Parents' : 'Dependents')
                    ->schema([
                        TextInput::make('name')->required(),
                        DatePicker::make('dob')->label('Date of Birth')->format('Y-m-d')->native(false),
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
                    ->hideLabels()
                    ->columnSpanFull(),
                Select::make('occupation_id')
                    ->relationship('occupation', 'name')
                    ->options(Occupation::pluck('name', 'id')),
                TextInput::make('occupation_description'),
                TextInput::make('highest_educational_attainment')
                    ->maxLength(125),
                TextInput::make('present_employer'),
                TextInput::make('annual_income')
                    ->moneymask()
                    ->minValue(0),
                TextInput::make('other_income_sources'),
                Section::make('Membership Acceptance')
                    ->schema([
                        TextInput::make('bod_resolution')->label('BOD Resolution'),
                        DatePicker::make('effectivity_date')->native(false)->label('Membership Date')->required()->default(fn($livewire) => $livewire->transaction_date),
                        Hidden::make('type')->default(MembershipStatus::ACCEPTANCE),
                    ])->relationship('membership_acceptance'),
                Section::make('Initial Capital Subscription')
                    ->hiddenOn('edit')
                    ->visible(fn($get) => $get('member_type_id'))
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
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
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
                TextColumn::make('civil_status.name')
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
                    ->visible(fn(HasTable $livewire) => $livewire->getTableFilterState('member_type') == 1)
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
                        fn($query, $state) => $query
                            ->when($state['value'] == 1, fn($q) => $q->whereNull('terminated_at'))
                            ->when($state['value'] == 2, fn($q) => $q->whereNotNull('terminated_at'))
                    ),
                SelectFilter::make('civil_status')
                    ->relationship('civil_status', 'name'),
                SelectFilter::make('occupation')
                    ->relationship('occupation', 'name'),
                SelectFilter::make('highest_educational_attainment')
                    ->options(Member::whereNotNull('highest_educational_attainment')
                        ->distinct('highest_educational_attainment')
                        ->pluck('highest_educational_attainment', 'highest_educational_attainment'))
                    ->searchable()
                    ->preload(),
                DateRangeFilter::make('membership_date')
                    ->format('m/d/Y')
                    ->displayFormat('MM/DD/YYYY')
                    ->label('Date of Membership'),
            ])
            ->filtersLayout(FiltersLayout::AboveContent)
            ->persistFiltersInSession()
            ->actions([
                Tables\Actions\Action::make('terminate')
                    ->requiresConfirmation()
                    ->button()
                    ->color(Color::Amber)
                    ->visible(Auth::user()->can('manage members'))
                    ->hidden(fn($record) => $record->terminated_at)
                    ->form([
                        TextInput::make('bod_resolution')->label('BOD Resolution')->required(),
                        DatePicker::make('termination_date')->default(config('app.transaction_date') ?? today())->native(false)->required(),
                    ])
                    ->action(function ($record, $data) {
                        DB::beginTransaction();
                        MembershipStatus::create([
                            'member_id' => $record->id,
                            'type' => MembershipStatus::TERMINATION,
                            'bod_resolution' => $data['bod_resolution'],
                            'effectivity_date' => $data['termination_date'],
                        ]);
                        $record->update(['terminated_at' => config('app.transaction_date') ?? now()]);
                        DB::commit();
                        Notification::make()->title('Member terminated.')->success()->send();
                    }),
                Tables\Actions\EditAction::make()
                    ->hidden(fn($record) => $record->terminated_at)
                    ->visible(Auth::user()->can('manage members')),
                Tables\Actions\DeleteAction::make()
                    ->hidden(fn($record) => $record->terminated_at)
                    ->form([
                        TextInput::make('passkey')
                            ->hint("Manager's Password")
                            ->required()
                            ->password(),
                    ])
                    ->action(function (Member $record, $data) {
                        if (! OverrideProvider::promptManagerPasskey($data['passkey'])) {
                            return;
                        }
                        DB::beginTransaction();
                        try {
                            if ($record->capital_subscription_payments()->count() <= 1) {
                                $record->capital_subscriptions()->delete();
                                $record->delete();
                            } else {
                                return Notification::make()->title('Member has existing payments.')->danger()->send();
                            }
                        } catch (\Throwable $th) {
                            DB::rollBack();

                            return Notification::make()->title('Member has existing payments.')->danger()->send();
                        }
                        Notification::make()->title('Member deleted.')->success()->send();
                        DB::commit();
                    })->visible(Auth::user()->can('manage members')),
            ])
            ->bulkActions([])
            ->emptyStateActions([])
            ->recordUrl(fn(Member $record) => MemberResource::getUrl('view', ['record' => $record]))
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
            'index' => Pages\ListMembers::route('/'),
            'create' => Pages\CreateMember::route('/create'),
            'report' => Pages\MembersReport::route('/reports'),
            'view' => Pages\ViewMember::route('/{record}'),
            'print' => Pages\PrintMemberProfile::route('/{member}/print'),
            'edit' => Pages\EditMember::route('/{record}/edit'),
            'loan.edit' => Pages\EditMemberLoan::route('/{record}/{loan}/edit'),
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
