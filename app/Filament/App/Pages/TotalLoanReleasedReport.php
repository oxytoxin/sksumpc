<?php

namespace App\Filament\App\Pages;

use App\Filament\App\Pages\Cashier\Reports\HasSignatories;
use App\Models\Loan;
use App\Models\LoanType;
use App\Models\Member;
use App\Models\MemberSubtype;
use App\Models\MemberType;
use App\Models\SignatureSet;
use App\Models\User;
use Auth;
use Filament\Forms\Components\Select;
use Filament\Pages\Page;
use Filament\Tables\Concerns\InteractsWithTable;
use Filament\Tables\Contracts\HasTable;
use Filament\Tables\Enums\FiltersLayout;
use Filament\Tables\Filters\Filter;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;
use Malzariey\FilamentDaterangepickerFilter\Filters\DateRangeFilter;

class TotalLoanReleasedReport extends Page implements HasTable
{
    use HasSignatories, InteractsWithTable;

    protected static string $view = 'filament.app.pages.total-loan-released-report';

    protected static ?string $navigationGroup = 'Loan';

    protected static ?int $navigationSort = 6;

    public static function shouldRegisterNavigation(): bool
    {
        return Auth::user()->can('manage loans');
    }


    protected function getSignatureSet()
    {
        return SignatureSet::where('name', 'Loan Officer Reports')->first();
    }

    public function table(Table $table): Table
    {
        return $table
            ->query(Loan::query()->wherePosted(true))
            ->content(fn() => view('filament.app.views.total-loan-released-report-table', [
                'signatories' => $this->signatories,
                'loan_types' => LoanType::query()
                    ->when($this->tableFilters['loan_type_id']['values'], fn($query, $value) => $query->whereIn('id', $value))
                    ->get(),
            ]))
            ->filters([
                SelectFilter::make('member_type')
                    ->relationship('member.member_type', 'name'),
                SelectFilter::make('member_subtype')
                    ->relationship('member.member_subtype', 'name'),
                SelectFilter::make('division')
                    ->relationship('member.division', 'name'),
                SelectFilter::make('patronage_status')
                    ->relationship('member.patronage_status', 'name'),
                SelectFilter::make('gender')
                    ->relationship('member.gender', 'name'),
                SelectFilter::make('status')
                    ->options([
                        1 => 'Active',
                        2 => 'Terminated',
                    ])
                    ->default(1)
                    ->query(
                        fn($query, $state) => $query
                            ->when($state['value'] == 1, fn($q) => $q->whereRelation('member', 'terminated_at', null))
                            ->when($state['value'] == 2, fn($q) => $q->whereRelation('member', 'terminated_at', '!=',  null))
                    ),
                SelectFilter::make('civil_status')
                    ->relationship('member.civil_status', 'name'),
                SelectFilter::make('occupation')
                    ->relationship('member.occupation', 'name'),
                SelectFilter::make('highest_educational_attainment')
                    ->label('Highest Educational Attainment')
                    ->options(Member::whereNotNull('highest_educational_attainment')
                        ->distinct('highest_educational_attainment')
                        ->pluck('highest_educational_attainment', 'highest_educational_attainment'))
                    ->searchable()
                    ->preload()
                    ->query(
                        fn($query, $state) => $query
                            ->when($state['value'], fn($q, $v) => $q->whereRelation('member', 'highest_educational_attainment', $v))
                    ),
                DateRangeFilter::make('release_date')
                    ->format('m/d/Y')
                    ->displayFormat('MM/DD/YYYY')
                    ->label('Release Date'),
                SelectFilter::make('loan_type_id')
                    ->multiple()
                    ->label('Loan Types')
                    ->preload()
                    ->relationship('loan_type', 'name'),
            ])
            ->filtersLayout(FiltersLayout::AboveContent)
            ->paginated(false);
    }
}
