<?php

namespace App\Filament\App\Resources\LoanApplicationResource\Pages;

use Filament\Actions;
use App\Models\LoanType;
use App\Models\LoanApplication;
use App\Oxytoxin\LoansProvider;
use Filament\Actions\CreateAction;
use Filament\Notifications\Notification;
use Filament\Resources\Pages\ManageRecords;
use App\Filament\App\Resources\LoanApplicationResource;

class ManageLoanApplications extends ManageRecords
{
    protected static string $resource = LoanApplicationResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make()
                ->action(function ($data) {
                    $data['monthly_payment'] = LoansProvider::computeMonthlyPayment($data['desired_amount'], LoanType::find($data['loan_type_id']), $data['number_of_terms'], $data['transaction_date']);
                    LoanApplication::create($data);
                    Notification::make()->title('New loan application created.')->success()->send();
                })
                ->visible(auth()->user()->can('manage loans'))
                ->createAnother(false),
        ];
    }
}
