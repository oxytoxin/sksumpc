<?php

namespace App\Filament\App\Resources;

use App\Actions\LoanBilling\PostLoanBillingPayments;
use App\Filament\App\Resources\LoanBillingResource\Pages;
use App\Models\LoanBilling;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Placeholder;
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
        return auth()->user()->can('manage loans') || auth()->user()->can('manage payments');
    }

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Select::make('loan_type_id')
                    ->relationship('loan_type', 'name')
                    ->required(),
                Select::make('payment_type_id')
                    ->paymenttype()
                    ->default(null)
                    ->selectablePlaceholder(true),
                DatePicker::make('date')
                    ->date()
                    ->afterOrEqual(today())
                    ->validationMessages([
                        'after_or_equal' => 'The date must be after or equal to today.',
                    ])
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
                TextColumn::make('created_at')->date('m/d/Y')->label('Date Generated'),
                TextColumn::make('reference_number'),
                IconColumn::make('or_approved')
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
                    ->visible(fn ($record, $livewire) => ! $record->posted && ! $record->or_approved && $livewire->user_is_loan_officer)
                    ->form([
                        Select::make('payment_type_id')
                            ->paymenttype()
                            ->default(null)
                            ->selectablePlaceholder(true),
                        TextInput::make('reference_number'),
                    ]),
                Tables\Actions\DeleteAction::make()
                    ->visible(fn ($record, $livewire) => ! $record->posted && ! $record->or_approved && $livewire->user_is_loan_officer)
                    ->action(function (LoanBilling $record) {
                        $record->loan_billing_payments()->delete();
                        $record->delete();
                    }),
                Action::make('approve_or')
                    ->button()
                    ->color('success')
                    ->visible(fn ($record, $livewire) => ! $record->posted && ! $record->or_approved && $livewire->user_is_cashier)
                    ->label('Approve OR')
                    ->requiresConfirmation()
                    ->form([
                        Placeholder::make('reference_number')->content(fn ($record) => $record->reference_number)->inlineLabel(),
                        Placeholder::make('amount')->content(fn ($record) => $record->loan_billing_payments()->sum('amount_paid'))->inlineLabel(),
                        Placeholder::make('payment_type')->content(fn ($record) => $record->payment_type->name)->inlineLabel(),
                        TextInput::make('name')->required(),
                        TextInput::make('or_number')->required()->label('OR #'),
                    ])
                    ->action(function (LoanBilling $record, $data) {
                        $record->update([
                            'name' => $data['name'],
                            'or_number' => $data['or_number'],
                            'or_approved' => true,
                        ]);
                        Notification::make()->title('OR Approved for loan billing!')->success()->send();
                    }),
                Action::make('post_payments')
                    ->button()
                    ->color('success')
                    ->visible(fn ($record, $livewire) => ! $record->posted && $record->or_approved && $livewire->user_is_loan_officer)
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
