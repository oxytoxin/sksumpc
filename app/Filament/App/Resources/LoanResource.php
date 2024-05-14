<?php

namespace App\Filament\App\Resources;

use App\Actions\Loans\ApproveLoanPosting;
use App\Actions\Transactions\CreateTransaction;
use App\Filament\App\Resources\LoanResource\Actions\ViewLoanDetailsActionGroup;
use App\Filament\App\Resources\LoanResource\Pages;
use App\Livewire\App\Loans\Traits\HasViewLoanDetailsActionGroup;
use App\Models\Account;
use App\Models\DisbursementVoucher;
use App\Models\Loan;
use App\Models\LoanType;
use App\Models\Member;
use App\Models\TransactionType;
use App\Oxytoxin\DTO\Transactions\TransactionData;
use App\Rules\BalancedBookkeepingEntries;
use Awcodes\FilamentTableRepeater\Components\TableRepeater;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Support\Enums\MaxWidth;
use Filament\Tables\Actions\Action;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Enums\FiltersLayout;
use Filament\Tables\Filters\Filter;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\DB;
use Malzariey\FilamentDaterangepickerFilter\Filters\DateRangeFilter;

class LoanResource extends Resource
{
    use HasViewLoanDetailsActionGroup;

    protected static ?string $model = Loan::class;

    protected static ?int $navigationSort = 5;

    protected static ?string $navigationGroup = 'Loan';

    protected static ?string $navigationLabel = 'Loans Posting';

    public static function shouldRegisterNavigation(): bool
    {
        return auth()->user()->canAny(['manage bookkeeping']);
    }

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                //
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('loan_account.number')->label('Account Number')->searchable(),
                TextColumn::make('member.full_name')->searchable(),
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
            ->actions([
                Action::make('dv')
                    ->label('DV')
                    ->action(fn (Loan $record) => app(ApproveLoanPosting::class)->handle($record))
                    ->hidden(fn ($record) => $record->posted)
                    ->modalWidth(MaxWidth::ScreenExtraLarge)
                    ->button()
                    ->fillForm(fn ($record) => [
                        'name' => $record->member->full_name,
                        'reference_number' => $record->reference_number,
                        'disbursement_voucher_items' => $record->disclosure_sheet_items,
                    ])
                    ->form([
                        TextInput::make('name')->required(),
                        TextInput::make('address')->required(),
                        TextInput::make('reference_number')->required(),
                        TextInput::make('voucher_number')->required(),
                        Textarea::make('description')->columnSpanFull()->required(),
                        TableRepeater::make('disbursement_voucher_items')
                            ->hideLabels()
                            ->columnSpanFull()
                            ->columnWidths(['account_id' => '20rem', 'member_id' => '20rem'])
                            ->rule(new BalancedBookkeepingEntries)
                            ->reactive()
                            ->afterStateUpdated(function ($get, $set, $state) {
                                $items = collect($state);
                                $net_amount = $items->firstWhere('code', 'net_amount');
                                $items = $items->filter(fn ($i) => $i['code'] != 'net_amount');
                                $net_amount['credit'] = $items->sum('debit') - $items->sum('credit');
                                $items->push($net_amount);
                                $set('disbursement_voucher_items', $items->toArray());
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
                                        fn ($get) => Account::withCode()->whereDoesntHave('children', fn ($q) => $q->whereNull('member_id'))->where('member_id', $get('member_id') ?? null)->pluck('code', 'id')
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
                    ->action(function ($data, $record) {
                        DB::beginTransaction();
                        $transactionType = TransactionType::firstWhere('name', 'CDJ');
                        $items = $data['disbursement_voucher_items'];
                        unset($data['disbursement_voucher_items'], $data['member_id']);
                        $data['voucher_type_id'] = 1;
                        $data['transaction_date'] = config('app.transaction_date') ?? today();
                        $dv = DisbursementVoucher::create($data);
                        $new_disclosure_sheet_items = [];
                        $accounts = Account::withCode()->find(collect($items)->pluck('account_id'));
                        foreach ($items as $item) {
                            $account = $accounts->find($item['account_id']);
                            $item['name'] = $account->code;
                            $new_disclosure_sheet_items[] = $item;
                            unset($item['member_id'], $item['code'], $item['name'], $item['readonly'], $item['loan_id']);
                            $dv->disbursement_voucher_items()->create($item);
                        }
                        $record->update([
                            'disbursement_voucher_id' => $dv->id,
                            'disclosure_sheet_items' => $new_disclosure_sheet_items,
                        ]);
                        app(ApproveLoanPosting::class)->handle($record);
                        DB::commit();
                    })
                    ->color('success')
                    ->icon('heroicon-o-shield-check'),
                ViewLoanDetailsActionGroup::getActions(),
                Action::make('print')
                    ->icon('heroicon-o-printer')
                    ->url(fn ($record) => route('filament.app.resources.loan-applications.application-form', ['loan_application' => $record->loan_application]), true),
            ])
            ->bulkActions([])
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
            'index' => Pages\ListLoans::route('/'),
        ];
    }
}
