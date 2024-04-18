<?php

namespace App\Filament\App\Resources\LoanApplicationResource\Pages;

use App\Filament\App\Resources\LoanApplicationResource;
use Awcodes\FilamentTableRepeater\Components\TableRepeater;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Form;
use Filament\Resources\Pages\Page;

class CreditAndBackgroundInvestigationReport extends Page
{
    protected static string $resource = LoanApplicationResource::class;

    protected static string $view = 'filament.app.resources.loan-application-resource.pages.credit-and-background-investigation-report';

    public function form(Form $form): Form
    {
        return $form->schema([
            Section::make('Basic Information')
                ->columns(2)
                ->schema([
                    Section::make('Borrower')
                        ->schema([
                            TextInput::make('name'),
                            TextInput::make('nickname'),
                            TextInput::make('middle_name'),
                            TextInput::make('date_of_birth'),
                            TextInput::make('age'),
                            TextInput::make('contact_number'),
                            TextInput::make('civil_status'),
                            TextInput::make('nationality'),
                            TextInput::make('address'),
                            TextInput::make('highest_educational_attainment'),
                            TextInput::make('school'),
                        ])->columnSpan(1),
                    Section::make('Spouse')
                        ->schema([
                            TextInput::make('name'),
                            TextInput::make('nickname'),
                            TextInput::make('middle_name'),
                            TextInput::make('date_of_birth'),
                            TextInput::make('age'),
                            TextInput::make('contact_number'),
                            TextInput::make('civil_status'),
                            TextInput::make('nationality'),
                            TextInput::make('address'),
                            TextInput::make('highest_educational_attainment'),
                            TextInput::make('school'),
                        ])->columnSpan(1)
                ]),
            TableRepeater::make('children')
                ->schema([
                    TextInput::make('name'),
                    DatePicker::make('birthdate')->time(false)->native(false),
                    TextInput::make('course_and_school'),
                ])
                ->hideLabels(),
            TableRepeater::make('assets')
                ->schema([
                    TextInput::make('name'),
                    TextInput::make('value'),
                    TextInput::make('status'),
                ])
                ->hideLabels(),
        ]);
    }
}
