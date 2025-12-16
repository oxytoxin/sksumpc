<?php

    namespace App\Filament\App\Pages;

    use App\Models\Loan;
    use App\Models\LoanPayment;
    use App\Models\Member;
    use Filament\Forms\Components\Select;
    use Filament\Forms\Concerns\InteractsWithForms;
    use Filament\Forms\Contracts\HasForms;
    use Filament\Pages\Page;
    use Filament\Schemas\Schema;

    class Casa extends Page implements HasForms
    {

        use InteractsWithForms;

        protected static string|\BackedEnum|null $navigationIcon = 'icon-casa';

        protected string $view = 'filament.app.pages.casa';

        protected static ?int $navigationSort = 13;

        public $data = [];

        public static function shouldRegisterNavigation(): bool
        {
            return false;
        }

        public function form(Schema $schema): Schema
        {
            return $schema
                ->reactive()
                ->components([
                    Select::make('member_id')
                        ->label('Member')
                        ->options(fn() => Member::pluck('full_name', 'id'))
                        ->searchable(),
                    Select::make('loan_id')
                        ->options(fn($get) => Loan::whereMemberId($get('member_id'))->pluck('reference_number', 'id')),
                    Select::make('loan_payment_id')
                        ->options(fn($get) => LoanPayment::whereLoanId($get('loan_id'))->pluck('reference_number', 'id'))
                ])->statePath('data');
        }

        public function mount()
        {
            $this->form->fill([
                'member_id' => 542,
                'loan_id' => 1165,
            ]);
        }

        public function selectLoanPayment(LoanPayment $loanPayment)
        {
            dd($loanPayment);
        }

    }
