<?php

namespace App\Filament\App\Resources\LoanApplicationResource\Pages;

use App\Filament\App\Pages\Cashier\Reports\HasSignatories;
use App\Filament\App\Resources\LoanApplicationResource;
use App\Models\LoanApplication;
use App\Models\SignatureSet;
use App\Models\User;
use Filament\Actions\Action;
use Filament\Forms\Components\Repeater;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Resources\Pages\Page;
use Livewire\Attributes\Computed;

class CoborrowerUndertaking extends Page
{
    protected static string $resource = LoanApplicationResource::class;

    protected string $view = 'filament.app.resources.loan-application-resource.pages.coborrower-undertaking';

    public LoanApplication $loan_application;

    use HasSignatories;

    public function mount(): void
    {
        $this->getSignatories();
    }

    protected function getHeaderActions(): array
    {
        return [
            Action::make('signatories')
                ->fillForm([
                    'signatories' => $this->signatories,
                ])
                ->form([
                    Repeater::make('signatories')
                        ->table([
                            Repeater\TableColumn::make('Action'),
                            Repeater\TableColumn::make('User'),
                            Repeater\TableColumn::make('Designation'),
                        ])
                        ->schema([
                            TextInput::make('action')->required(),
                            Select::make('user_id')
                                ->label('User')
                                ->options(User::pluck('name', 'id'))
                                ->searchable()
                                ->required(),
                            TextInput::make('designation')->required(),
                        ])
                        ->label('')
                        ->addActionLabel('Add Signatory')
                        ->reorderableWithDragAndDrop(),
                ])
                ->action(function (array $data) {
                    $this->signatories = array_map(function ($signatory) {
                        $user = User::find($signatory['user_id']);

                        return [
                            'user_id' => $signatory['user_id'],
                            'name' => $user ? $user->name : '',
                            'action' => $signatory['action'],
                            'designation' => $signatory['designation'],
                        ];
                    }, $data['signatories']);
                }),
        ];
    }

    #[Computed]
    public function loanApplication()
    {
        return $this->loan_application;
    }

    protected function getSignatureSet()
    {
        return SignatureSet::where('name', 'Coborrower Undertaking')->first();
    }

    protected function getAdditionalSignatories()
    {

        if ($this->loan_application->desired_amount > $this->loan_application->loan_type->approval_threshold) {
            $user = User::whereRelation('roles', 'name', 'bod-chairperson')->first();
            $designation = 'BOD-Chairperson';
        } else {
            $user = User::whereRelation('roles', 'name', 'manager')->first();
            $designation = 'Manager';
        }

        $signatories = [];

        if ($user) {
            $signatories[] = [
                'user_id' => $user->id,
                'name' => $user->name,
                'action' => 'Signed in the presence of:',
                'designation' => $designation,
            ];
        }

        return $signatories;
    }

    protected function readOnlySignatories(): bool
    {
        return false;
    }
}
