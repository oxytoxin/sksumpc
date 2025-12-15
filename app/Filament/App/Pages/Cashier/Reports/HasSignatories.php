<?php

    namespace App\Filament\App\Pages\Cashier\Reports;

    use App\Models\SignatureSet;
    use Filament\Actions\Action;
    use Filament\Forms\Components\Repeater;
    use Filament\Forms\Components\Select;
    use Filament\Forms\Components\TextInput;

    trait HasSignatories
    {
        public $signatories = [];

        public $signature_set;

        protected function readOnlySignatories()
        {
            return false;
        }

        protected function getHeaderActions(): array
        {
            if ($this->readOnlySignatories()) {
                return [];
            }

            return [
                Action::make('signatories')
                    ->fillForm([
                        'signatories' => $this->signatories,
                    ])
                    ->schema(function () {
                        return [
                            Repeater::make('signatories')
                                ->table([
                                    Repeater\TableColumn::make('Action'),
                                    Repeater\TableColumn::make('User'),
                                    Repeater\TableColumn::make('Designation'),
                                ])
                                ->schema([
                                    TextInput::make('action'),
                                    Select::make('user_id')->relationship('user', 'name')->required()->searchable()->preload(),
                                    TextInput::make('designation')->required(),
                                ])
                                ->model($this->getSignatureSet())
                                ->relationship('signatories')
                                ->label('')
                                ->addActionLabel('Add Signatory'),
                        ];
                    })->action(fn() => $this->getSignatories()),
            ];
        }

        protected function getSignatureSet()
        {
            return SignatureSet::where('name', 'Cashier Reports')->first();
        }

        protected function getAdditionalSignatories()
        {
            return [];
        }

        public function getSignatories()
        {
            $this->signatories = $this->getSignatureSet()->signatories->map(fn($s) => [
                'user_id' => $s->user_id,
                'name' => $s->user->name,
                'action' => $s->action,
                'designation' => $s->designation,
            ])->toArray();
            $this->signatories = [...$this->signatories, ...$this->getAdditionalSignatories()];

            return $this->signatories;
        }
    }
