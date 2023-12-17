<?php

namespace App\Filament\App\Resources;

use Filament\Forms;
use Filament\Tables;
use Filament\Forms\Form;
use Filament\Tables\Table;
use App\Models\LoanBilling;
use Filament\Resources\Resource;
use Filament\Forms\Components\Select;
use Filament\Tables\Actions\EditAction;
use Filament\Forms\Components\TextInput;
use Filament\Tables\Actions\DeleteAction;
use Illuminate\Database\Eloquent\Builder;
use Filament\Tables\Actions\BulkActionGroup;
use Filament\Tables\Actions\DeleteBulkAction;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use App\Filament\App\Resources\LoanBillingResource\Pages;
use App\Filament\App\Resources\LoanBillingResource\RelationManagers;
use App\Filament\App\Resources\LoanBillingResource\Pages\ManageLoanBillings;
use App\Models\LoanBillingPayment;
use DB;
use Filament\Forms\Components\DatePicker;
use Filament\Notifications\Notification;
use Filament\Tables\Actions\Action;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\TextColumn;

class LoanBillingResource extends Resource
{
    protected static ?string $model = LoanBilling::class;

    protected static ?string $navigationGroup = 'Loan';

    protected static ?int $navigationSort = 5;

    public static function shouldRegisterNavigation(): bool
    {
        return auth()->user()->can('manage loans');
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
                TextInput::make('reference_number')
                    ->unique('loan_billings'),
                DatePicker::make('date')
                    ->date()
                    ->afterOrEqual(today())
                    ->validationMessages([
                        'after_or_equal' => 'The date must be after or equal to today.'
                    ])
                    ->required()
                    ->native(false)
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
                IconColumn::make('posted')
                    ->boolean()
            ])
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\EditAction::make()
                    ->visible(fn ($record) => !$record->posted)
                    ->form([
                        Select::make('payment_type_id')
                            ->paymenttype()
                            ->default(null)
                            ->selectablePlaceholder(true),
                        TextInput::make('reference_number')
                    ]),
                Tables\Actions\DeleteAction::make()
                    ->visible(fn ($record) => !$record->posted)
                    ->action(function (LoanBilling $record) {
                        $record->loan_billing_payments()->delete();
                        $record->delete();
                    }),
                Action::make('post_payments')
                    ->button()
                    ->color('success')
                    ->visible(fn ($record) => !$record->posted)
                    ->requiresConfirmation()
                    ->action(function (LoanBilling $record) {
                        if (!$record->reference_number || !$record->payment_type_id) {
                            return Notification::make()->title('Billing reference number and payment type is missing!')->danger()->send();
                        }
                        DB::beginTransaction();
                        $record->loan_billing_payments()->each(function (LoanBillingPayment $lp) use ($record) {
                            $loan = $lp->loan_amortization->loan;
                            $amortization = $lp->loan_amortization;
                            $amortization->update([
                                'amount_paid' => $lp->amount_paid,
                            ]);
                            $loan->payments()->createQuietly([
                                'cashier_id' => auth()->id(),
                                'principal_payment' => $amortization->amount_paid - $amortization->interest,
                                'payment_type_id' => $record->payment_type_id,
                                'reference_number' => $record->reference_number,
                                'amount' => $lp->amount_paid,
                                'transaction_date' => today(),
                            ]);
                            $lp->update([
                                'posted' => true
                            ]);
                        });
                        $record->update([
                            'posted' => true
                        ]);
                        DB::commit();
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
