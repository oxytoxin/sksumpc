<?php

namespace App\Filament\App\Pages\Cashier\Reports;

use App\Models\User;
use Awcodes\FilamentTableRepeater\Components\TableRepeater;
use Filament\Actions\Action;
use Filament\Forms\Components\TextInput;

trait HasSignatories
{
    public $signatories = [];

    protected function getHeaderActions(): array
    {
        return [
            Action::make('signatories')
                ->fillForm([
                    'signatories' => $this->signatories,
                ])
                ->form(function () {
                    return [
                        TableRepeater::make('signatories')
                            ->schema([
                                TextInput::make('action'),
                                TextInput::make('name')->required(),
                                TextInput::make('position')->required(),
                            ])
                            ->hideLabels()
                            ->label('')
                            ->addActionLabel('Add Signatory'),
                    ];
                })->action(fn ($data) => $this->signatories = $data['signatories']),
        ];
    }

    protected function getSignatories()
    {
        $bookkeeper = User::whereRelation('roles', 'name', 'book-keeper')->first();
        $treasurer = User::whereRelation('roles', 'name', 'treasurer')->first();
        $manager = User::whereRelation('roles', 'name', 'manager')->first();
        $this->signatories = [
            [
                'action' => 'Prepared by:',
                'name' => auth()->user()->name,
                'position' => 'Teller/Cashier',
            ],
            [
                'action' => 'Checked by:',
                'name' => $bookkeeper?->name ?? 'ADRIAN VOLTAIRE POLO',
                'position' => 'Posting Clerk',
            ],
            [
                'action' => 'Received by:',
                'name' => $treasurer?->name ?? 'DESIREE G. LEGASPI',
                'position' => 'Treasurer',
            ],
            [
                'action' => 'Noted:',
                'name' => $manager?->name ?? 'FLORA C. DAMANDAMAN',
                'position' => 'Manager',
            ],
        ];
    }

    public function mountHasSignatories()
    {
        $this->getSignatories();
    }
}
