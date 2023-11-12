<?php

namespace App\Filament\App\Resources\LoanApplicationResource\Pages;

use App\Filament\App\Resources\LoanApplicationResource;
use App\Models\LoanApplication;
use App\Models\User;
use Filament\Resources\Pages\Page;

class LoanApplicationForm extends Page
{
    protected static string $resource = LoanApplicationResource::class;

    protected static string $view = 'filament.app.resources.loan-application-resource.pages.loan-application-form';

    public LoanApplication $loan_application;
    public $approvers = [];

    public function mount()
    {
        $approver_users = User::findMany(collect($this->loan_application->approvals->items())->pluck('approver_id')->values());
        $this->approvers = collect($this->loan_application->approvals->items())->map(function ($a) use ($approver_users) {
            return [
                'name' => $approver_users->firstWhere('id', $a->approver_id)?->name,
                'position' => $a->position
            ];
        });
    }
}
