<?php

namespace App\Filament\App\Resources;

use App\Filament\App\Resources\MemberResource\Pages;
use App\Filament\App\Resources\MemberResource\RelationManagers;
use App\Infolists\Components\DependentsEntry;
use App\Models\Member;
use App\Models\MembershipStatus;
use App\Models\MemberType;
use App\Models\Occupation;
use App\Models\Religion;
use Awcodes\FilamentTableRepeater\Components\TableRepeater;
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
use Filament\Infolists\Components\Actions;
use Filament\Infolists\Components\Actions\Action;
use Filament\Infolists\Components\Grid as InfolistGrid;
use Filament\Infolists\Components\Section as InfolistSection;
use Filament\Infolists\Components\SpatieMediaLibraryImageEntry;
use Filament\Infolists\Components\Tabs;
use Filament\Infolists\Components\Tabs\Tab;
use Filament\Infolists\Components\TextEntry;
use Filament\Infolists\Infolist;
use Filament\Notifications\Notification;
use Filament\Resources\Resource;
use Filament\Support\Enums\IconPosition;
use Filament\Tables;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;

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
                                    TextEntry::make('civil_status')->formatStateUsing(fn ($state) => match ($state) {
                                        'S' => 'Single',
                                        'M' => 'Married',
                                        'W' => 'Widowed',
                                    })->extraAttributes(['class' => 'font-semibold'])->inlineLabel(),
                                    TextEntry::make('contact_number')->extraAttributes(['class' => 'font-semibold'])->inlineLabel(),
                                    TextEntry::make('religion.name')->extraAttributes(['class' => 'font-semibold'])->inlineLabel(),
                                    TextEntry::make('highest_educational_attainment')->extraAttributes(['class' => 'font-semibold'])->inlineLabel(),
                                ]),
                                InfolistSection::make()
                                    ->schema([
                                        TextEntry::make('occupation.name')->extraAttributes(['class' => 'font-semibold'])->inlineLabel(),
                                        TextEntry::make('present_employer')->extraAttributes(['class' => 'font-semibold'])->inlineLabel(),
                                        TextEntry::make('annual_income')->money('PHP')->extraAttributes(['class' => 'font-semibold'])->inlineLabel(),
                                        TextEntry::make('other_income_sources')->extraAttributes(['class' => 'font-semibold'])->inlineLabel(),
                                    ]),
                                InfolistSection::make()
                                    ->schema([
                                        DependentsEntry::make('dependents')->label('Number of Dependents'),
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
                            ->schema([]),
                        Tab::make('Savings')
                            ->schema([]),
                        Tab::make('Loan')
                            ->schema([]),
                    ])
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
                                    ->required(),
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
                            ->dehydrateStateUsing(fn ($state) => strtoupper($state))
                            ->maxLength(1),
                        Forms\Components\DatePicker::make('dob')
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
                        Forms\Components\Select::make('civil_status')
                            ->options([
                                'S' => 'Single',
                                'M' => 'Married',
                                'W' => 'Widowed',
                            ])
                            ->default('S'),
                        Forms\Components\Select::make('religion_id')
                            ->relationship('religion', 'name')
                            ->options(Religion::pluck('name', 'id')),
                    ]),
                TableRepeater::make('dependents')
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
                Forms\Components\TagsInput::make('other_income_sources')
                    ->placeholder('Add Income Source'),
                Forms\Components\TextInput::make('highest_educational_attainment')
                    ->maxLength(125),
                Forms\Components\TextInput::make('present_employer'),
                Forms\Components\TextInput::make('annual_income')
                    ->numeric()
                    ->prefix('P')
                    ->minValue(0),

                Section::make('Membership Acceptance')
                    ->schema([
                        TextInput::make('bod_resolution')->numeric(),
                        DatePicker::make('effectivity_date')->required()->default(today()),
                        Hidden::make('type')->default(MembershipStatus::ACCEPTANCE)
                    ])->relationship('membership_acceptance'),
                Section::make('Initial Capital Subscription')
                    ->schema([
                        TextInput::make('number_of_shares')->numeric()->required(),
                        TextInput::make('amount_subscribed')->numeric()->required(),
                        TextInput::make('initial_amount_paid')->numeric()->prefix('P')->required(),
                        Hidden::make('is_ics')->default(true)
                    ])->relationship('initial_capital_subscription')
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('id')
                    ->label('ID')
                    ->searchable(),
                Tables\Columns\TextColumn::make('first_name')
                    ->searchable(),
                Tables\Columns\TextColumn::make('last_name')
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
                Tables\Columns\TextColumn::make('civil_status')
                    ->alignCenter(),
                Tables\Columns\TextColumn::make('gender')
                    ->alignCenter(),
                Tables\Columns\TextColumn::make('tin')
                    ->label('TIN')
                    ->alignCenter()
                    ->searchable(),
                Tables\Columns\TextColumn::make('member_type.name')
                    ->sortable(),
                // Tables\Columns\TextColumn::make('address')
                //     ->searchable()
                //     ->toggleable(isToggledHiddenByDefault: true),
                // Tables\Columns\TextColumn::make('occupation.name')
                //     ->toggleable(isToggledHiddenByDefault: true),
                // Tables\Columns\TextColumn::make('highest_educational_attainment')
                //     ->searchable()
                //     ->toggleable(isToggledHiddenByDefault: true),
                // Tables\Columns\TextColumn::make('dependents_count')
                //     ->label('Dependents')
                //     ->numeric()
                //     ->sortable()
                //     ->toggleable(isToggledHiddenByDefault: true),
                // Tables\Columns\TextColumn::make('religion.name')
                //     ->toggleable(isToggledHiddenByDefault: true),
                // Tables\Columns\TextColumn::make('annual_income')
                //     ->money('PHP')
                //     ->sortable()
                //     ->toggleable(isToggledHiddenByDefault: true),
                // Tables\Columns\TextColumn::make('accepted_at')
                //     ->date()
                //     ->sortable()
                //     ->toggleable(isToggledHiddenByDefault: true),
                // Tables\Columns\TextColumn::make('acceptance_bod_resolution')
                //     ->searchable()
                //     ->toggleable(isToggledHiddenByDefault: true),
                // Tables\Columns\TextColumn::make('terminated_at')
                //     ->date()
                //     ->sortable()
                //     ->toggleable(isToggledHiddenByDefault: true),
                // Tables\Columns\TextColumn::make('termination_bod_resolution')
                //     ->searchable()
                //     ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                SelectFilter::make('member_type')
                    ->relationship('member_type', 'name')
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
            ])
            ->bulkActions([])
            ->emptyStateActions([
                Tables\Actions\CreateAction::make(),
            ])
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
            'index' => Pages\ListMembers::route('/'),
            'create' => Pages\CreateMember::route('/create'),
            'view' => Pages\ViewMember::route('/{record}'),
            'edit' => Pages\EditMember::route('/{record}/edit'),
        ];
    }
}
