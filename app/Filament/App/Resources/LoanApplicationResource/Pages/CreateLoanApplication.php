<?php

namespace App\Filament\App\Resources\LoanApplicationResource\Pages;

use App\Filament\App\Resources\LoanApplicationResource;
use App\Models\LoanApplication;
use App\Models\LoanType;
use App\Oxytoxin\LoansProvider;
use Filament\Actions;
use Filament\Resources\Pages\CreateRecord;
use Illuminate\Database\Eloquent\Model;

class CreateLoanApplication extends CreateRecord
{
    protected static string $resource = LoanApplicationResource::class;

    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }

    protected function handleRecordCreation(array $data): Model
    {
        $data['monthly_payment'] = LoansProvider::computeMonthlyPayment($data['desired_amount'], LoanType::find($data['loan_type_id']), $data['number_of_terms'], $data['transaction_date']);
        return LoanApplication::create($data);
    }
}
