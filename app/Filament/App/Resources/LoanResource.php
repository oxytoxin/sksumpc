<?php

namespace App\Filament\App\Resources;

use App\Models\Loan;
use App\Models\Member;
use App\Models\Account;
use App\Models\LoanType;
use Filament\Forms\Form;
use App\Rules\BalancedBookkeepingEntries;
use Filament\Tables\Table;
use App\Models\TransactionType;
use Filament\Resources\Resource;
use Illuminate\Support\Facades\DB;
use App\Models\DisbursementVoucher;
use Filament\Tables\Actions\Action;
use Filament\Tables\Filters\Filter;
use Filament\Support\Enums\MaxWidth;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Forms\Components\TextInput;
use Filament\Tables\Enums\FiltersLayout;
use App\Actions\Loans\ApproveLoanPosting;
use Filament\Forms\Components\DatePicker;
use Filament\Tables\Filters\SelectFilter;
use Illuminate\Database\Eloquent\Builder;
use App\Actions\Transactions\CreateTransaction;
use App\Filament\App\Resources\LoanResource\Pages;
use App\Oxytoxin\DTO\Transactions\TransactionData;
use Awcodes\FilamentTableRepeater\Components\TableRepeater;
use App\Filament\App\Resources\LoanResource\Pages\ListLoans;
use App\Livewire\App\Loans\Traits\HasViewLoanDetailsActionGroup;

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
                Filter::make('date_applied')
                    ->form([
                        DatePicker::make('applied_from')->native(false),
                        DatePicker::make('applied_until')->native(false),
                    ])
                    ->query(function (Builder $query, array $data): Builder {
                        return $query
                            ->when(
                                $data['applied_from'],
                                fn (Builder $query, $date): Builder => $query->whereDate('transaction_date', '>=', $date),
                            )
                            ->when(
                                $data['applied_until'],
                                fn (Builder $query, $date): Builder => $query->whereDate('transaction_date', '<=', $date),
                            );
                    }),
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
                        'disbursement_voucher_items' => $record->disclosure_sheet_items
                    ])
                    ->form([
                        TextInput::make('name')->required(),
                        TextInput::make('address')->required(),
                        TextInput::make('reference_number')->required(),
                        Textarea::make('description')->columnSpanFull()->required(),
                        TableRepeater::make('disbursement_voucher_items')
                            ->hideLabels()
                            ->columnSpanFull()
                            ->columnWidths(['account_id' => '20rem', 'member_id' => '20rem'])
                            ->rule(new BalancedBookkeepingEntries)
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
                        $dv = DisbursementVoucher::create($data);
                        $new_disclosure_sheet_items = [];
                        foreach ($items as $item) {
                            $account = Account::withCode()->find($item['account_id']);
                            $item['name'] = $account->code;
                            $new_disclosure_sheet_items[] = $item;
                            app(CreateTransaction::class)->handle(new TransactionData(
                                member_id: $item['member_id'],
                                account_id: $account->id,
                                transactionType: $transactionType,
                                reference_number: $dv->reference_number,
                                debit: $item['debit'],
                                credit: $item['credit'],
                            ));
                            unset($item['member_id']);
                            unset($item['code']);
                            unset($item['name']);
                            unset($item['readonly']);
                            unset($item['loan_id']);
                            $dv->disbursement_voucher_items()->create($item);
                        }
                        $record->update([
                            'disclosure_sheet_items' => $new_disclosure_sheet_items
                        ]);
                        app(ApproveLoanPosting::class)->handle($record);
                        DB::commit();
                    })
                    ->color('success')
                    ->icon('heroicon-o-shield-check'),
                static::getStaticViewLoanDetailsActionGroup(),
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
