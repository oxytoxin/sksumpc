<?php

namespace App\Filament\App\Resources\JournalEntryVoucherResource\Pages;

use App\Actions\JournalEntryVouchers\JevFromImprestsToLoan;
use App\Actions\JournalEntryVouchers\JevFromSavingsToLoan;
use App\Filament\App\Resources\JournalEntryVoucherResource;
use App\Models\ImprestAccount;
use App\Models\Loan;
use App\Models\SavingsAccount;
use App\Oxytoxin\DTO\JournalEntryVoucher\JournalEntryVoucherData;
use Filament\Actions\Action;
use Filament\Actions\CreateAction;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Grid;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Notifications\Notification;
use Filament\Resources\Pages\ManageRecords;

class ManageJournalEntryVouchers extends ManageRecords
{
    protected static string $resource = JournalEntryVoucherResource::class;

    private function getJevFormComponents(array $formComponents)
    {
        return [
            Grid::make(2)->schema([
                TextInput::make('name')->required(),
                DatePicker::make('transaction_date')->required()->default(today())->native(false),
                TextInput::make('address')->required(),
                TextInput::make('reference_number')->required(),
            ]),
            Textarea::make('description')->required(),
            ...$formComponents,
            TextInput::make('amount')
                ->required()
                ->moneymask(),
        ];
    }

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make()
                ->icon('heroicon-o-plus')
                ->label('JEV')
                ->createAnother(false),
            Action::make('savings_to_loan')
                ->label('JEV From Savings To Loan')
                ->action(function ($data) {
                    $savings_account = SavingsAccount::find($data['savings_account_id']);
                    $loan = Loan::find($data['loan_id']);
                    app(JevFromSavingsToLoan::class)->handle(
                        new JournalEntryVoucherData(
                            name: $data['name'],
                            address: $data['address'],
                            reference_number: $data['reference_number'],
                            description: $data['description'],
                        ),
                        savingsAccount: $savings_account,
                        loan: $loan,
                        amount: $data['amount']
                    );
                    Notification::make()->title('JEV from savings to loan successful!')->success()->send();
                })
                ->form([
                    ...$this->getJevFormComponents([
                        Select::make('savings_account_id')
                            ->label('Savings Account')
                            ->options(SavingsAccount::pluck('number', 'id'))
                            ->searchable()
                            ->required(),
                        Select::make('loan_id')
                            ->label('Loan Account')
                            ->options(function ($get) {
                                $savings_account = SavingsAccount::find($get('savings_account_id'));
                                if ($savings_account) {
                                    return Loan::whereMemberId($savings_account->member_id)->payable()->pluck('account_number', 'id');
                                }

                                return [];
                            })
                            ->searchable()
                            ->required(),
                    ]),
                ]),
            Action::make('imprests_to_loan')
                ->label('JEV From Imprests To Loan')
                ->action(function ($data) {
                    $member = ImprestAccount::find($data['imprest_account_id'])?->member;
                    $loan = Loan::find($data['loan_id']);
                    app(JevFromImprestsToLoan::class)->handle(
                        new JournalEntryVoucherData(
                            name: $data['name'],
                            address: $data['address'],
                            reference_number: $data['reference_number'],
                            description: $data['description'],
                        ),
                        member: $member,
                        loan: $loan,
                        amount: $data['amount']
                    );
                    Notification::make()->title('JEV from imprests to loan successful!')->success()->send();
                })
                ->form([
                    ...$this->getJevFormComponents([
                        Select::make('imprest_account_id')
                            ->label('Imprest Account')
                            ->options(ImprestAccount::pluck('number', 'id'))
                            ->searchable()
                            ->required(),
                        Select::make('loan_id')
                            ->label('Loan Account')
                            ->options(function ($get) {
                                $imprest_account = ImprestAccount::find($get('imprest_account_id'));
                                if ($imprest_account) {
                                    return Loan::whereMemberId($imprest_account->member_id)->payable()->pluck('account_number', 'id');
                                }

                                return [];
                            })
                            ->searchable()
                            ->required(),
                    ]),
                ]),
        ];
    }
}
