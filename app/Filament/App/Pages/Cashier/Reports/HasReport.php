<?php

namespace App\Filament\App\Pages\Cashier\Reports;

use Filament\Tables\Concerns\InteractsWithTable;
use Filament\Tables\Enums\FiltersLayout;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Model;
use Malzariey\FilamentDaterangepickerFilter\Filters\DateRangeFilter;

trait HasReport
{
    use InteractsWithTable;

    public $report_title;

    public function table(Table $table): Table
    {
        return $table
            ->query($this->getReportQuery())
            ->content(fn () => view('filament.app.pages.cashier.reports.report-table', [
                'signatories' => $this->signatories,
                'report_title' => $this->report_title,
            ]))
            ->filters([
                DateRangeFilter::make('transaction_date')
                    ->format('m/d/Y')
                    ->defaultToday()
                    ->displayFormat('MM/DD/YYYY'),
            ])
            ->filtersLayout(FiltersLayout::AboveContent)
            ->paginated(false);
    }

    private function getReportQuery()
    {
        return Model::query();
    }
}
