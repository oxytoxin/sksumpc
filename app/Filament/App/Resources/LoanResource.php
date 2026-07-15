<?php

namespace App\Filament\App\Resources;

use App\Actions\Loans\CreateLoanVoucher;
use App\Filament\App\Resources\LoanResource\Actions\ViewLoanDetailsActionGroup;
use App\Filament\App\Resources\LoanResource\Pages\ListLoans;
use App\Livewire\App\Loans\Traits\HasViewLoanDetailsActionGroup;
use App\Models\Account;
use App\Models\JournalEntryVoucher;
use App\Models\Loan;
use App\Models\LoanType;
use App\Models\Member;
use App\Rules\BalancedBookkeepingEntries;
use Auth;
use Filament\Actions\Action;
use Filament\Forms\Components\Repeater;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Enums\Width;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Enums\FiltersLayout;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;
use Malzariey\FilamentDaterangepickerFilter\Filters\DateRangeFilter;

class LoanResource extends Resource
{
    use HasViewLoanDetailsActionGroup;

    protected static ?string $model = Loan::class;

    protected static ?int $navigationSort = 5;

    protected static string|\UnitEnum|null $navigationGroup = 'Loan';

    protected static ?string $label = 'Loans For Voucher';

    public static function shouldRegisterNavigation(): bool
    {
        return Auth::user()->canAny(['manage bookkeeping']);
    }

    public static function form(Schema $schema): Schema
    {
        return $schema
            ->components([
                //
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('loan_account.number')->label('Account Number')->searchable(),
                TextColumn::make('member.full_name')->searchable(),
                TextColumn::make('reference_number')->searchable(),
                TextColumn::make('check_number')->searchable(),
                TextColumn::make('loan_type.name'),
                TextColumn::make('gross_amount')->money('PHP'),
                TextColumn::make('deductions_amount')->money('PHP'),
                TextColumn::make('net_amount')->money('PHP'),
                IconColumn::make('posted')->boolean()->alignCenter(),
            ])
            ->filters([
                SelectFilter::make('loan_type_id')
                    ->label('Loan Type')
                    ->options(LoanType::orderBy('name')->pluck('name', 'id')),
                SelectFilter::make('posted')
                    ->options([
                        true => 'Posted',
                        false => 'Pending',
                    ]),
                DateRangeFilter::make('transaction_date')
                    ->format('m/d/Y')
                    ->displayFormat('MM/DD/YYYY'),
            ])
            ->filtersLayout(FiltersLayout::AboveContent)
            ->recordActions([
                Action::make('voucher')
                    ->label(fn (Loan $record): string => $record->isZeroNetAmount() ? 'JEV' : 'DV')
                    ->modalHeading(fn (Loan $record): string => $record->isZeroNetAmount() ? 'Create Journal Entry Voucher' : 'Create Disbursement Voucher')
                    ->hidden(fn (Loan $record): bool => $record->posted)
                    ->disabled(fn (Loan $record): bool => $record->hasNegativeNetAmount())
                    ->tooltip(fn (Loan $record): ?string => $record->hasNegativeNetAmount() ? 'Loans with a negative net amount cannot be posted.' : null)
                    ->modalWidth(Width::ScreenExtraLarge)
                    ->button()
                    ->fillForm(function (Loan $record): array {
                        $generatedReference = $record->isZeroNetAmount()
                            ? JournalEntryVoucher::generateCode(config('app.transaction_date') ?? today())
                            : null;

                        return [
                            'name' => $record->member->full_name,
                            'reference_number' => $generatedReference ?? $record->reference_number,
                            'check_number' => $record->check_number,
                            'voucher_number' => $generatedReference,
                            'voucher_items' => $record->disclosure_sheet_items,
                        ];
                    })
                    ->schema([
                        TextInput::make('name')->required(),
                        TextInput::make('address')->required(),
                        TextInput::make('reference_number')->required(),
                        TextInput::make('check_number')
                            ->hidden(fn (Loan $record): bool => $record->isZeroNetAmount()),
                        TextInput::make('voucher_number')->required(),
                        Textarea::make('description')->columnSpanFull()->required(),
                        Repeater::make('voucher_items')
                            ->columnSpanFull()
                            ->table([
                                Repeater\TableColumn::make('Member')->width('20rem'),
                                Repeater\TableColumn::make('Account')->width('20rem'),
                                Repeater\TableColumn::make('Debit'),
                                Repeater\TableColumn::make('Credit'),
                            ])
                            ->rule(new BalancedBookkeepingEntries)
                            ->reactive()
                            ->afterStateUpdated(function ($get, $set, $state) {
                                $items = collect($state);
                                $netAmount = $items->firstWhere('code', 'net_amount');

                                if (! $netAmount) {
                                    return;
                                }

                                $items = $items->filter(fn (array $item): bool => ($item['code'] ?? null) !== 'net_amount');
                                $netAmount['credit'] = $items->sum('debit') - $items->sum('credit');
                                $items->push($netAmount);
                                $set('voucher_items', $items->toArray());
                            })
                            ->schema([
                                Select::make('member_id')
                                    ->options(Member::pluck('full_name', 'id'))
                                    ->label('Member')
                                    ->searchable()
                                    ->reactive()
                                    ->preload(),
                                Select::make('account_id')
                                    ->options(
                                        fn ($get) => Account::withCode()->pluck('code', 'id')
                                    )
                                    ->searchable()
                                    ->required()
                                    ->label('Account'),
                                TextInput::make('debit')
                                    ->moneymask(),
                                TextInput::make('credit')
                                    ->moneymask(),
                            ]),
                    ])
                    ->action(fn (array $data, Loan $record) => app(CreateLoanVoucher::class)->handle($record, $data))
                    ->color('success')
                    ->icon('heroicon-o-shield-check'),
                ViewLoanDetailsActionGroup::getActions(),
                Action::make('print')
                    ->icon('heroicon-o-printer')
                    ->url(fn ($record) => route('filament.app.resources.loan-applications.application-form', ['loan_application' => $record->loan_application]), true),
            ])
            ->toolbarActions([])
            ->emptyStateActions([]);
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
            'index' => ListLoans::route('/'),
        ];
    }
}
