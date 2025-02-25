<?php

namespace App\Filament\App\Resources;

use App\Actions\MsoBilling\PostMsoBillingPayments;
use App\Filament\App\Resources\MsoBillingResource\Pages;
use App\Models\MemberSubtype;
use App\Models\MemberType;
use App\Models\MsoBilling;
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

class MsoBillingResource extends Resource
{
    protected static ?string $model = MsoBilling::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    public static function shouldRegisterNavigation(): bool
    {
        return false;
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
                Select::make('type')
                    ->label('MSO Type')
                    ->reactive()
                    ->required()
                    ->options([
                        1 => 'Savings',
                        2 => 'Imprest',
                        3 => 'Love Gift',
                    ]),
                Select::make('payment_type_id')
                    ->paymenttype()
                    ->required()
                    ->default(null)
                    ->selectablePlaceholder(true),
                DatePicker::make('date')
                    ->disabled()
                    ->dehydrated()
                    ->default(config('app.transaction_date'))
                    ->required()
                    ->native(false),
                TextInput::make('amount')->numeric()->gte(0),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
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
                //
            ])
            ->actions([
                Tables\Actions\EditAction::make()
                    ->visible(fn($record) => ! $record->posted)
                    ->form([
                        Select::make('payment_type_id')
                            ->paymenttype()
                            ->default(null)
                            ->selectablePlaceholder(true),
                        TextInput::make('reference_number'),
                    ]),
                Tables\Actions\DeleteAction::make()
                    ->visible(fn($record) => ! $record->posted)
                    ->action(function (MsoBilling $record) {
                        $record->payments()->delete();
                        $record->delete();
                    }),
                Action::make('for_or')
                    ->button()
                    ->color('success')
                    ->visible(fn($record, $livewire) => ! $record->posted && ! $record->for_or && ! $record->or_number && $livewire->user_is_cashier)
                    ->label('For OR')
                    ->requiresConfirmation()
                    ->action(function (MsoBilling $record) {
                        if ($record->payments()->doesntExist()) {
                            Notification::make()->title('No content, Subject for Review')->danger()->send();

                            return;
                        }
                        $record->update([
                            'for_or' => true,
                        ]);
                        Notification::make()->title('MSO billing for OR by Cashier!')->success()->send();
                    }),
                Action::make('post_payments')
                    ->button()
                    ->color('success')
                    ->visible(fn($record, $livewire) => ! $record->posted && ! $record->for_or && $record->or_number && $livewire->user_is_cbu_officer)
                    ->requiresConfirmation()
                    ->action(function (MsoBilling $record) {
                        app(PostMsoBillingPayments::class)->handle($record);
                        Notification::make()->title('Payments posted!')->success()->send();
                    }),
                Action::make('billing_receivables')
                    ->url(fn($record) => route('filament.app.resources.mso-billings.billing-payments', ['mso_billing' => $record]))
                    ->button()
                    ->outlined(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ManageMsoBillings::route('/'),
            'billing-payments' => Pages\MsoBillingPayments::route('/{mso_billing}/receivables'),
        ];
    }
}
