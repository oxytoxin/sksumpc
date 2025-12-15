<?php

namespace App\Filament\App\Resources;

use Filament\Schemas\Schema;
use Filament\Actions\EditAction;
use Filament\Actions\DeleteAction;
use Filament\Actions\Action;
use App\Filament\App\Resources\LoanBillingResource\Pages\ManageLoanBillings;
use App\Filament\App\Resources\LoanBillingResource\Pages\LoanBillingPayments;
use App\Filament\App\Resources\LoanBillingResource\Pages\PrintLoanBilling;
use App\Actions\LoanBilling\PostLoanBillingPayments;
use App\Filament\App\Resources\LoanBillingResource\Pages;
use App\Models\LoanBilling;
use App\Models\MemberSubtype;
use App\Models\MemberType;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Notifications\Notification;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Enums\FiltersLayout;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;

class LoanBillingResource extends Resource
{
    protected static ?string $model = LoanBilling::class;

    protected static string | \UnitEnum | null $navigationGroup = 'Loan';

    protected static ?int $navigationSort = 5;

    public static function shouldRegisterNavigation(): bool
    {
        return false;
    }

    public static function form(Schema $schema): Schema
    {
        return $schema
            ->components([
                Select::make('member_type_id')
                    ->label('Member Type')
                    ->reactive()
                    ->options(MemberType::pluck('name', 'id')),
                Select::make('member_subtype_id')
                    ->label('Member Subtype')
                    ->visible(fn ($get) => MemberSubtype::whereMemberTypeId($get('member_type_id'))->count())
                    ->options(fn ($get) => MemberSubtype::whereMemberTypeId($get('member_type_id'))->pluck('name', 'id')),
                Select::make('loan_type_id')
                    ->relationship('loan_type', 'name')
                    ->required(),
                Select::make('payment_type_id')
                    ->paymenttype()
                    ->default(null)
                    ->selectablePlaceholder(true),
                DatePicker::make('date')
                    ->disabled()
                    ->dehydrated()
                    ->default(config('app.transaction_date'))
                    ->required()
                    ->native(false),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('loan_type.name'),
                TextColumn::make('billable_date'),
                TextColumn::make('date')->date('m/d/Y')->label('Date Generated'),
                TextColumn::make('reference_number'),
                IconColumn::make('for_or')
                    ->label('For OR')
                    ->boolean(),
                TextColumn::make('or_number')
                    ->label('OR Number'),
                TextColumn::make('or_date')->date('m/d/Y')->label('OR Date'),
                IconColumn::make('or_approved')
                    ->label('OR Approved')
                    ->boolean(),
                IconColumn::make('posted')
                    ->boolean(),
            ])
            ->filters([
                SelectFilter::make('status')
                    ->options([
                        'posted' => 'Posted',
                        'for_or' => 'For OR',
                        'unposted' => 'Unposted',
                    ])
                    ->query(fn ($query, $state) => $query
                        ->when($state['value'] == 'posted', fn ($q) => $q->where('posted', true))
                        ->when($state['value'] == 'for_or', fn ($q) => $q->where('for_or', true))
                        ->when($state['value'] == 'unposted', fn ($q) => $q->where('posted', false)->whereNotNull('or_number'))
                    ),
            ])
            ->filtersLayout(FiltersLayout::AboveContent)
            ->recordActions([
                EditAction::make()
                    ->visible(fn ($record, $livewire) => ! $record->posted && $livewire->user_is_loan_officer)
                    ->schema([
                        Select::make('payment_type_id')
                            ->paymenttype()
                            ->default(null)
                            ->selectablePlaceholder(true),
                        TextInput::make('reference_number'),
                    ]),
                DeleteAction::make()
                    ->visible(fn ($record, $livewire) => ! $record->posted && ! $record->or_number && $livewire->user_is_loan_officer)
                    ->action(function (LoanBilling $record) {
                        $record->loan_billing_payments()->delete();
                        $record->delete();
                    }),
                Action::make('for_or')
                    ->button()
                    ->color('success')
                    ->visible(fn ($record, $livewire) => $record->can_for_or && $livewire->user_is_cashier)
                    ->label('For OR')
                    ->requiresConfirmation()
                    ->action(function (LoanBilling $record) {
                        if ($record->loan_billing_payments()->doesntExist()) {
                            Notification::make()->title('No content, Subject for Review')->danger()->send();

                            return;
                        }
                        $record->update([
                            'for_or' => true,
                        ]);
                        Notification::make()->title('Loan billing for OR by Cashier!')->success()->send();
                    }),
                Action::make('post_payments')
                    ->button()
                    ->color('success')
                    ->visible(fn ($record, $livewire) => $record->can_post_payments && $livewire->user_is_loan_officer)
                    ->requiresConfirmation()
                    ->action(function (LoanBilling $record) {
                        app(PostLoanBillingPayments::class)->handle(loanBilling: $record);
                        Notification::make()->title('Payments posted!')->success()->send();
                    }),
                Action::make('billing_receivables')
                    ->url(fn ($record) => route('filament.app.resources.loan-billings.billing-payments', ['loan_billing' => $record]))
                    ->button()
                    ->outlined(),
                Action::make('print')
                    ->url(fn ($record) => route('filament.app.resources.loan-billings.statement-of-remittance', ['loan_billing' => $record]))
                    ->icon('heroicon-o-printer')
                    ->button()
                    ->outlined(),
            ])
            ->toolbarActions([]);
    }

    public static function getPages(): array
    {
        return [
            'index' => ManageLoanBillings::route('/'),
            'billing-payments' => LoanBillingPayments::route('/{loan_billing}/receivables'),
            'statement-of-remittance' => PrintLoanBilling::route('/{loan_billing}/statement-of-remittance'),
        ];
    }
}
