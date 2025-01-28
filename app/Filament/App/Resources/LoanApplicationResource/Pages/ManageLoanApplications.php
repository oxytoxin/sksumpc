<?php

namespace App\Filament\App\Resources\LoanApplicationResource\Pages;

use App\Actions\LoanApplications\CreateNewLoanApplication;
use App\Filament\App\Pages\Cashier\RequiresBookkeeperTransactionDate;
use App\Filament\App\Resources\LoanApplicationResource;
use App\Models\LoanType;
use App\Oxytoxin\DTO\Loan\LoanApplicationData;
use App\Oxytoxin\Providers\LoansProvider;
use Auth;
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
        data_set($this, 'tableFilters.transaction_date.transaction_date', (config('app.transaction_date')?->format('m/d/Y') ?? today()->format('m/d/Y')) . ' - ' . (config('app.transaction_date')?->format('m/d/Y') ?? today()->format('m/d/Y')));
    }

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make()
                ->mutateFormDataUsing(function ($data) {
                    $data['transaction_date'] = config('app.transaction_date') ?? today();
                    $data['monthly_payment'] = LoansProvider::computeMonthlyPayment($data['desired_amount'], LoanType::find($data['loan_type_id']), $data['number_of_terms'], config('app.transaction_date') ?? today());
                    return $data;
                })
                ->visible(Auth::user()->can('manage loans'))
                ->createAnother(false),
        ];
    }
}
