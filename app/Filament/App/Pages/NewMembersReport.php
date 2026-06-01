<?php

namespace App\Filament\App\Pages;

use App\Filament\App\Pages\Cashier\Reports\HasSignatories;
use App\Models\Member;
use Filament\Pages\Page;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Concerns\InteractsWithTable;
use Filament\Tables\Contracts\HasTable;
use Filament\Tables\Enums\FiltersLayout;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;

class NewMembersReport extends Page implements HasTable
{
    use HasSignatories, InteractsWithTable;

    protected static bool $shouldRegisterNavigation = false;

    protected static ?string $title = 'NEW MEMBERS';

    protected string $view = 'filament.app.pages.new-members-report';

    public $report_title = 'REPORT ON NEW MEMBERS';

    public function table(Table $table): Table
    {
        return $table
            ->query(
                fn () => Member::query()
                    ->whereNotNull('membership_date')
                    ->with(['membership_acceptance', 'initial_capital_subscription'])
                    ->orderBy('membership_date')
            )
            ->columns([
                TextColumn::make('full_name')
                    ->label('Member Name')
                    ->searchable(),
                TextColumn::make('membership_date')
                    ->label('Membership Date')
                    ->date('m/d/Y'),
                TextColumn::make('membership_acceptance.bod_resolution')
                    ->label('BOD Resolution'),
                TextColumn::make('initial_capital_subscription.initial_amount_paid')
                    ->label('Initial Paid Up Capital')
                    ->numeric(decimalPlaces: 2),
            ])
            ->content(fn () => view('filament.app.pages.new-members-report-table', [
                'signatories' => $this->getSignatories(),
                'report_title' => $this->report_title,
            ]))
            ->filters([
                SelectFilter::make('membership_year')
                    ->label('Year')
                    ->options(
                        fn () => Member::selectRaw('DISTINCT YEAR(membership_date) as year')
                            ->whereNotNull('membership_date')
                            ->orderBy('year', 'desc')
                            ->pluck('year', 'year')
                            ->map(fn ($y) => (string) $y)
                    )
                    ->default(now()->year)
                    ->query(function ($query, $state) {
                        if (filled($state)) {
                            $query->whereYear('membership_date', $state);
                        }
                    }),
                SelectFilter::make('member_type')
                    ->relationship('member_type', 'name'),
                SelectFilter::make('division')
                    ->relationship('division', 'name'),
            ])
            ->filtersLayout(FiltersLayout::AboveContent)
            ->paginated(false);
    }
}
