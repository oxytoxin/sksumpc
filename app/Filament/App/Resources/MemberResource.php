<?php

namespace App\Filament\App\Resources;

use App\Filament\App\Resources\MemberResource\Pages;
use App\Filament\App\Resources\MemberResource\Pages\CbuSubsidiaryLedger;
use App\Filament\App\Resources\MemberResource\Pages\ImprestSubsidiaryLedger;
use App\Filament\App\Resources\MemberResource\Pages\LoanAmortizationSchedule;
use App\Filament\App\Resources\MemberResource\Pages\LoanSubsidiaryLedger;
use App\Filament\App\Resources\MemberResource\Pages\SavingsSubsidiaryLedger;
use App\Filament\App\Resources\MemberResource\RelationManagers;
use App\Infolists\Components\DependentsEntry;
use App\Models\CapitalSubscription;
use App\Models\Member;
use App\Models\MembershipStatus;
use App\Models\MemberType;
use App\Models\Occupation;
use App\Models\Religion;
use App\Oxytoxin\ShareCapitalProvider;
use Awcodes\FilamentTableRepeater\Components\TableRepeater;
use DB;
use Filament\Forms;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Fieldset;
use Filament\Forms\Components\Grid;
use Filament\Forms\Components\Hidden;
use Filament\Forms\Components\Placeholder;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\SpatieMediaLibraryFileUpload;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Form;
use Filament\Forms\Get;
use Filament\Infolists\Components\Actions;
use Filament\Infolists\Components\Actions\Action;
use Filament\Infolists\Components\Grid as InfolistGrid;
use Filament\Infolists\Components\Section as InfolistSection;
use Filament\Infolists\Components\SpatieMediaLibraryImageEntry;
use Filament\Infolists\Components\Tabs;
use Filament\Infolists\Components\Tabs\Tab;
use Filament\Infolists\Components\TextEntry;
use Filament\Infolists\Components\ViewEntry;
use Filament\Infolists\Infolist;
use Filament\Notifications\Notification;
use Filament\Resources\Resource;
use Filament\Support\Enums\IconPosition;
use Filament\Support\RawJs;
use Filament\Tables;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;

use function Filament\Support\format_money;

class MemberResource extends Resource
{
    protected static ?string $model = Member::class;

    protected static ?string $navigationIcon = 'icon-membership';

    protected static ?string $navigationLabel = 'Membership';

    protected static ?int $navigationSort = 3;

    public static function infolist(Infolist $infolist): Infolist
    {
        return $infolist
            ->schema([
                Tabs::make()
                    ->tabs([
                        Tab::make('Profile')
                            ->schema([
                                InfolistSection::make([
                                    SpatieMediaLibraryImageEntry::make('profile_photo')
                                        ->label('')
                                        ->collection('profile_photo')->circular()
                                        ->visible(fn ($record) => $record->getFirstMedia('profile_photo')),
                                    TextEntry::make('full_name')->extraAttributes(['class' => 'font-semibold'])->inlineLabel()->alignStart(),
                                    TextEntry::make('dob')->label('Date of Birth')->date('F d, Y')->extraAttributes(['class' => 'font-semibold'])->inlineLabel(),
                                    TextEntry::make('place_of_birth')->label('Place of Birth')->extraAttributes(['class' => 'font-semibold'])->inlineLabel(),
                                    TextEntry::make('gender')->formatStateUsing(fn ($state) => match ($state) {
                                        'M' => 'Male',
                                        'F' => 'Female',
                                    })->extraAttributes(['class' => 'font-semibold'])->inlineLabel(),
                                    TextEntry::make('civil_status.name')->extraAttributes(['class' => 'font-semibold'])->inlineLabel(),
                                    TextEntry::make('contact_number')->extraAttributes(['class' => 'font-semibold'])->inlineLabel(),
                                    TextEntry::make('religion.name')->extraAttributes(['class' => 'font-semibold'])->inlineLabel(),
                                    TextEntry::make('highest_educational_attainment')->extraAttributes(['class' => 'font-semibold'])->inlineLabel(),
                                    TextEntry::make('tin')->extraAttributes(['class' => 'font-semibold'])->inlineLabel()->label('TIN'),
                                    TextEntry::make('member_type.name')->extraAttributes(['class' => 'font-semibold'])->inlineLabel()->label('Member Type'),
                                    TextEntry::make('division.name')->extraAttributes(['class' => 'font-semibold'])->inlineLabel(),
                                ]),
                                InfolistSection::make()
                                    ->schema([
                                        TextEntry::make('occupation.name')->extraAttributes(['class' => 'font-semibold'])->inlineLabel(),
                                        TextEntry::make('present_employer')->extraAttributes(['class' => 'font-semibold'])->inlineLabel(),
                                        TextEntry::make('annual_income')->money('PHP')->extraAttributes(['class' => 'font-semibold'])->inlineLabel(),
                                        TextEntry::make('other_income_sources')->money('PHP')->extraAttributes(['class' => 'font-semibold'])->inlineLabel(),
                                    ]),
                                InfolistSection::make()
                                    ->schema([
                                        DependentsEntry::make('dependents')->label('Number of Dependents')
                                            ->inlineLabel(),
                                    ]),
                                InfolistSection::make('Initial Capital Subscription')
                                    ->schema([
                                        TextEntry::make('membership_acceptance.effectivity_date')->label('Date Accepted')->date('F d, Y')->extraAttributes(['class' => 'font-semibold'])->inlineLabel(),
                                        TextEntry::make('membership_acceptance.bod_resolution')->label('BOD Resolution')->extraAttributes(['class' => 'font-semibold'])->inlineLabel(),
                                        TextEntry::make('member_type.name')->label('Type of Member')->extraAttributes(['class' => 'font-semibold'])->inlineLabel(),
                                        TextEntry::make('initial_capital_subscription.number_of_shares')->label('# of Shares Subscribed')->extraAttributes(['class' => 'font-semibold'])->inlineLabel(),
                                        TextEntry::make('initial_capital_subscription.amount_subscribed')->label('Amount Subscribed')->money('PHP')->extraAttributes(['class' => 'font-semibold'])->inlineLabel(),
                                        TextEntry::make('initial_capital_subscription.initial_amount_paid')->label('Initial Amount Paid-up')->money('PHP')->extraAttributes(['class' => 'font-semibold'])->inlineLabel(),
                                    ]),
                            ]),
                        Tab::make('CBU')
                            ->schema([
                                ViewEntry::make('cbu')
                                    ->view('filament.app.views.cbu-table')
                            ]),
                        Tab::make('MSO')
                            ->schema([
                                ViewEntry::make('mso')
                                    ->view('filament.app.views.mso-table')
                            ]),
                        Tab::make('Loan')
                            ->schema([
                                ViewEntry::make('loan')
                                    ->view('filament.app.views.loans-table')
                            ]),
                    ])->persistTabInQueryString()
            ])
            ->columns(1);
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
                                Forms\Components\Select::make('member_type_id')
                                    ->relationship('member_type', 'name')
                                    ->live()
                                    ->afterStateUpdated(function ($state, $set) {
                                        if ($state == 1) {
                                            $set('present_employer', 'SKSU-Sultan Kudarat State University');
                                            $set('number_of_shares', ShareCapitalProvider::REGULAR_INITIAL_SHARES);
                                            $set('amount_subscribed', number_format(ShareCapitalProvider::REGULAR_INITIAL_AMOUNT, 2));
                                            $set('initial_amount_paid', number_format(ShareCapitalProvider::REGULAR_INITIAL_PAID, 2));
                                            $set('monthly_payment', number_format(ShareCapitalProvider::fromNumberOfShares(ShareCapitalProvider::REGULAR_INITIAL_SHARES, ShareCapitalProvider::INITIAL_NUMBER_OF_TERMS)['monthly_payment'], 2));
                                            return;
                                        }
                                        $set('present_employer', '');
                                        if ($state == 2) {
                                            $set('number_of_shares', ShareCapitalProvider::ASSOCIATE_INITIAL_SHARES);
                                            $set('amount_subscribed', number_format(ShareCapitalProvider::ASSOCIATE_INITIAL_AMOUNT, 2));
                                            $set('initial_amount_paid', number_format(ShareCapitalProvider::ASSOCIATE_INITIAL_PAID, 2));
                                            $set('monthly_payment', number_format(ShareCapitalProvider::fromNumberOfShares(ShareCapitalProvider::ASSOCIATE_INITIAL_SHARES, ShareCapitalProvider::INITIAL_NUMBER_OF_TERMS)['monthly_payment'], 2));
                                            return;
                                        }
                                    })
                                    ->required(),
                                Forms\Components\Select::make('division_id')
                                    ->relationship('division', 'name'),
                                Forms\Components\TextInput::make('tin')
                                    ->label('TIN')
                                    ->maxLength(30),
                            ])
                            ->columnSpan(1),
                    ]),
                Grid::make(3)
                    ->schema([
                        Forms\Components\TextInput::make('first_name')
                            ->label('First Name')
                            ->required()
                            ->maxLength(125),
                        Forms\Components\TextInput::make('last_name')
                            ->label('Last Name')
                            ->required()
                            ->maxLength(125),
                        Forms\Components\TextInput::make('middle_initial')
                            ->label('MI')
                            ->dehydrateStateUsing(fn ($state) => $state ? strtoupper($state) : null)
                            ->maxLength(1),
                        Forms\Components\DatePicker::make('dob')
                            ->before(today()->subYearsNoOverflow(10))
                            ->validationAttribute('Date of Birth')
                            ->required()
                            ->label('Date of Birth'),
                        Forms\Components\TextInput::make('place_of_birth')
                            ->label('Place of Birth')
                            ->columnSpan(2),
                        Forms\Components\Textarea::make('address')
                            ->maxLength(125)
                            ->columnSpanFull(),
                    ]),
                Grid::make(3)
                    ->schema([
                        Forms\Components\Select::make('gender')
                            ->options([
                                'M' => 'Male',
                                'F' => 'Female',
                            ]),
                        Forms\Components\Select::make('civil_status_id')
                            ->relationship('civil_status', 'name')
                            ->default(1),
                        Forms\Components\Select::make('religion_id')
                            ->relationship('religion', 'name')
                            ->options(Religion::pluck('name', 'id')),
                    ]),
                TableRepeater::make('dependents')
                    ->default([])
                    ->schema([
                        TextInput::make('name'),
                        DatePicker::make('dob')->label('Date of Birth'),
                        Select::make('relationship')
                            ->options([
                                'FATHER' => 'FATHER',
                                'MOTHER' => 'MOTHER',
                                'SON' => 'SON',
                                'DAUGHTER' => 'DAUGHTER',
                                'COUSIN' => 'COUSIN',
                                'OTHERS' => 'OTHERS',
                            ])
                    ])
                    ->hideLabels()
                    ->columnSpanFull(),
                Forms\Components\Select::make('occupation_id')
                    ->relationship('occupation', 'name')
                    ->options(Occupation::pluck('name', 'id')),

                Forms\Components\TextInput::make('highest_educational_attainment')
                    ->maxLength(125),
                Forms\Components\TextInput::make('present_employer'),
                Forms\Components\TextInput::make('annual_income')
                    ->mask(fn ($state) => RawJs::make('$money'))
                    ->dehydrateStateUsing(fn ($state) => str_replace(',', '', $state ?? 0))
                    ->prefix('P')
                    ->minValue(0),
                Forms\Components\TextInput::make('other_income_sources')
                    ->mask(fn ($state) => RawJs::make('$money'))
                    ->dehydrateStateUsing(fn ($state) => str_replace(',', '', $state ?? 0))
                    ->prefix('P'),

                Section::make('Membership Acceptance')
                    ->schema([
                        TextInput::make('bod_resolution')->numeric(),
                        DatePicker::make('effectivity_date')->required()->default(today()),
                        Hidden::make('type')->default(MembershipStatus::ACCEPTANCE)
                    ])->relationship('membership_acceptance'),
                Section::make('Initial Capital Subscription')
                    ->hiddenOn('edit')
                    ->schema([
                        TextInput::make('number_of_terms')->readOnly()->minValue(0)->default(ShareCapitalProvider::INITIAL_NUMBER_OF_TERMS),
                        TextInput::make('number_of_shares')->minValue(0)->default(0)
                            ->live(true)
                            ->afterStateUpdated(function ($set, $state, $get) {
                                $data = ShareCapitalProvider::fromNumberOfShares($state, ShareCapitalProvider::INITIAL_NUMBER_OF_TERMS);
                                $set('amount_subscribed', $data['amount_subscribed']);
                                $set('monthly_payment', $data['monthly_payment']);
                            }),
                        TextInput::make('amount_subscribed')->prefix('P')
                            ->mask(fn ($state) => RawJs::make('$money'))
                            ->dehydrateStateUsing(fn ($state) => str_replace(',', '', $state ?? 0))
                            ->minValue(0)->default(0)
                            ->live(true)
                            ->afterStateUpdated(function ($set, $state, $get) {
                                $data = ShareCapitalProvider::fromAmountSubscribed($state, ShareCapitalProvider::INITIAL_NUMBER_OF_TERMS);
                                $set('monthly_payment', $data['monthly_payment']);
                                $set('number_of_shares', $data['number_of_shares']);
                            }),
                        TextInput::make('monthly_payment')->prefix('P')
                            ->mask(fn ($state) => RawJs::make('$money'))
                            ->dehydrateStateUsing(fn ($state) => str_replace(',', '', $state ?? 0))
                            ->minValue(0)->default(0)
                            ->live(true)
                            ->afterStateUpdated(function ($set, $state, $get) {
                                $data = ShareCapitalProvider::fromMonthlyPayment($state, ShareCapitalProvider::INITIAL_NUMBER_OF_TERMS);
                                $set('amount_subscribed', $data['amount_subscribed']);
                                $set('number_of_shares', $data['number_of_shares']);
                            }),
                        TextInput::make('initial_amount_paid')->prefix('P')
                            ->mask(fn ($state) => RawJs::make('$money'))
                            ->dehydrateStateUsing(fn ($state) => str_replace(',', '', $state ?? 0))
                            ->minValue(0)->default(0),
                        Hidden::make('code')->default(ShareCapitalProvider::INITIAL_CAPITAL_CODE),
                    ])
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('mpc_code')
                    ->label('Code')
                    ->sortable()
                    ->searchable(),
                Tables\Columns\TextColumn::make('first_name')
                    ->sortable()
                    ->searchable(),
                Tables\Columns\TextColumn::make('last_name')
                    ->sortable()
                    ->searchable(),
                Tables\Columns\TextColumn::make('middle_initial')
                    ->label('MI')
                    ->alignCenter(),
                Tables\Columns\TextColumn::make('dob')
                    ->label('Date of Birth')
                    ->date('F d, Y')
                    ->sortable(),
                Tables\Columns\TextColumn::make('age')
                    ->numeric()
                    ->alignCenter()
                    ->sortable(),
                Tables\Columns\TextColumn::make('civil_status.name')
                    ->alignCenter(),
                Tables\Columns\TextColumn::make('gender')
                    ->alignCenter(),
                Tables\Columns\TextColumn::make('tin')
                    ->label('TIN')
                    ->alignCenter()
                    ->searchable(),
                Tables\Columns\TextColumn::make('member_type.name')
                    ->sortable(),
            ])
            ->filters([
                SelectFilter::make('member_type')
                    ->relationship('member_type', 'name')
            ])
            ->persistFiltersInSession()
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make()
                    ->action(function (Member $record) {
                        try {
                            $record->delete();
                        } catch (\Throwable $th) {
                            Notification::make()->title('Member has existing records.')->danger()->send();
                        }
                    })
            ])
            ->bulkActions([])
            ->emptyStateActions([
                Tables\Actions\CreateAction::make(),
            ])
            ->recordUrl(fn (Member $record) => MemberResource::getUrl('view', ['record' => $record]))
            ->defaultSort('last_name')
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
            'view' => Pages\ViewMember::route('/{record}'),
            'edit' => Pages\EditMember::route('/{record}/edit'),
            'cbu-subsidiary-ledger' => CbuSubsidiaryLedger::route('cbu-subsidiary-ledger/{member}'),
            'savings-subsidiary-ledger' => SavingsSubsidiaryLedger::route('savings-subsidiary-ledger/{member}'),
            'imprest-subsidiary-ledger' => ImprestSubsidiaryLedger::route('imprest-subsidiary-ledger/{member}'),
            'loan-subsidiary-ledger' => LoanSubsidiaryLedger::route('loan-subsidiary-ledger/{loan}'),
            'loan-amortization-schedule' => LoanAmortizationSchedule::route('loan-amortization-schedule/{loan}'),
        ];
    }
}
