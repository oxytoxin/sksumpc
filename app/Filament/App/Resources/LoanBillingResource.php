<?php

namespace App\Filament\App\Resources;

use App\Actions\LoanBilling\PostLoanBillingPayments;
use App\Filament\App\Resources\LoanBillingResource\Pages;
use App\Models\LoanBilling;
use App\Models\MemberSubtype;
use App\Models\MemberType;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Form;
use Filament\Notifications\Notification;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Actions\Action;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class LoanBillingResource extends Resource
{
    protected static ?string $model = LoanBilling::class;

    protected static ?string $navigationGroup = 'Loan';

    protected static ?int $navigationSort = 5;

    public static function shouldRegisterNavigation(): bool
    {
        return auth()->user()->can('manage loans') || auth()->user()->can('manage payments') || auth()->user()->can('handle clerical tasks');
    }

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Select::make('member_type_id')
                    ->label('Member Type')
                    ->reactive()
                    ->options(MemberType::pluck('name', 'id')),
                Select::make('member_subtype_id')
                    ->label('Member Subtype')
                    ->visible(fn($get) => MemberSubtype::whereMemberTypeId($get('member_type_id'))->count())
                    ->options(fn($get) => MemberSubtype::whereMemberTypeId($get('member_type_id'))->pluck('name', 'id')),
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
                IconColumn::make('or_number')
                    ->label('OR Approved')
                    ->boolean(),
                IconColumn::make('posted')
                    ->boolean(),
            ])
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\EditAction::make()
                    ->visible(fn($record, $livewire) => !$record->posted && !$record->or_number && $livewire->user_is_loan_officer)
                    ->form([
                        Select::make('payment_type_id')
                            ->paymenttype()
                            ->default(null)
                            ->selectablePlaceholder(true),
                        TextInput::make('reference_number'),
                    ]),
                Tables\Actions\DeleteAction::make()
                    ->visible(fn($record, $livewire) => !$record->posted && !$record->or_number && $livewire->user_is_loan_officer)
                    ->action(function (LoanBilling $record) {
                        $record->loan_billing_payments()->delete();
                        $record->delete();
                    }),
                Action::make('for_or')
                    ->button()
                    ->color('success')
                    ->visible(fn($record, $livewire) => !$record->posted && !$record->for_or && !$record->or_number && $livewire->user_is_cashier)
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
                    ->visible(fn($record, $livewire) => !$record->posted && !$record->for_or && $record->or_number && $livewire->user_is_loan_officer)
                    ->requiresConfirmation()
                    ->action(function (LoanBilling $record) {
                        app(PostLoanBillingPayments::class)->handle(loanBilling: $record);
                        Notification::make()->title('Payments posted!')->success()->send();
                    }),
                Action::make('billing_receivables')
                    ->url(fn($record) => route('filament.app.resources.loan-billings.billing-payments', ['loan_billing' => $record]))
                    ->button()
                    ->outlined(),
                Action::make('print')
                    ->url(fn($record) => route('filament.app.resources.loan-billings.statement-of-remittance', ['loan_billing' => $record]))
                    ->icon('heroicon-o-printer')
                    ->button()
                    ->outlined(),
            ])
            ->bulkActions([]);
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ManageLoanBillings::route('/'),
            'billing-payments' => Pages\LoanBillingPayments::route('/{loan_billing}/receivables'),
            'statement-of-remittance' => Pages\PrintLoanBilling::route('/{loan_billing}/statement-of-remittance'),
        ];
    }
}
