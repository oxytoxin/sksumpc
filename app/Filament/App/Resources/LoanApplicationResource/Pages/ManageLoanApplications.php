<?php

namespace App\Filament\App\Resources\LoanApplicationResource\Pages;

use App\Actions\LoanApplications\CreateNewLoanApplication;
use App\Filament\App\Pages\Cashier\RequiresBookkeeperTransactionDate;
use App\Filament\App\Resources\LoanApplicationResource;
use App\Models\LoanType;
use App\Oxytoxin\DTO\Loan\LoanApplicationData;
use App\Oxytoxin\Providers\LoansProvider;
use Filament\Actions\CreateAction;
use Filament\Notifications\Notification;
use Filament\Resources\Pages\ManageRecords;

class ManageLoanApplications extends ManageRecords
{
    use RequiresBookkeeperTransactionDate;

    protected static string $resource = LoanApplicationResource::class;

    public function mount(): void
    {
        parent::mount();
        data_set($this, 'tableFilters.transaction_date.transaction_date', (config('app.transaction_date')->format('m/d/Y') ?? today()->format('m/d/Y')) . ' - ' . config('app.transaction_date')->format('m/d/Y') ?? today()->format('m/d/Y'));
    }
    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make()
                ->action(function ($data) {
                    $loan_application_data = new LoanApplicationData(
                        member_id: $data['member_id'],
                        loan_type_id: $data['loan_type_id'],
                        number_of_terms: $data['number_of_terms'],
                        priority_number: $data['priority_number'],
                        desired_amount: $data['desired_amount'],
                        monthly_payment: LoansProvider::computeMonthlyPayment($data['desired_amount'], LoanType::find($data['loan_type_id']), $data['number_of_terms'], today()),
                        purpose: $data['purpose'],
                        comakers: $data['comakers'],
                        transaction_date: config('app.transaction_date')
                    );
                    app(CreateNewLoanApplication::class)->handle($loan_application_data);
                    Notification::make()->title('New loan application created.')->success()->send();
                })
                ->visible(auth()->user()->can('manage loans'))
                ->createAnother(false),
        ];
    }
}
