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

class TerminatedMembersReport extends Page implements HasTable
{
    use HasSignatories, InteractsWithTable;

    protected static bool $shouldRegisterNavigation = false;

    protected static ?string $title = 'TERMINATED MEMBERS';

    protected string $view = 'filament.app.pages.terminated-members-report';

    public $report_title = 'REPORT ON TERMINATED MEMBERS';

    public function table(Table $table): Table
    {
        return $table
            ->query(
                fn () => Member::query()
                    ->whereNotNull('terminated_at')
                    ->with(['member_type', 'division'])
                    ->orderBy('terminated_at', 'desc')
            )
            ->columns([
                TextColumn::make('mpc_code')
                    ->label('MPC Code')
                    ->searchable(),
                TextColumn::make('full_name')
                    ->label('Member Name')
                    ->searchable(),
                TextColumn::make('membership_date')
                    ->label('Membership Date')
                    ->date('m/d/Y'),
                TextColumn::make('terminated_at')
                    ->label('Date Terminated')
                    ->dateTime('m/d/Y'),
                TextColumn::make('member_type.name')
                    ->label('Member Type'),
                TextColumn::make('division.name')
                    ->label('Division'),
            ])
            ->content(fn () => view('filament.app.pages.terminated-members-report-table', [
                'signatories' => $this->getSignatories(),
                'report_title' => $this->report_title,
            ]))
            ->filters([
                SelectFilter::make('termination_year')
                    ->label('Year')
                    ->options(
                        fn () => Member::selectRaw('DISTINCT YEAR(terminated_at) as year')
                            ->whereNotNull('terminated_at')
                            ->orderBy('year', 'desc')
                            ->pluck('year', 'year')
                            ->map(fn ($y) => (string) $y)
                    )
                    ->default(now()->year)
                    ->query(function ($query, $state) {
                        if (filled($state)) {
                            $query->whereYear('terminated_at', $state);
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
