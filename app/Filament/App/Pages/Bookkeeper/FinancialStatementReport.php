<?php

namespace App\Filament\App\Pages\Bookkeeper;

use App\Models\LoanType;
use Filament\Actions\Action;
use Filament\Actions\Concerns\InteractsWithActions;
use Filament\Actions\Contracts\HasActions;
use Filament\Pages\Page;
use PhpOffice\PhpSpreadsheet\IOFactory;

class FinancialStatementReport extends Page implements HasActions
{
    use InteractsWithActions;

    protected static string $view = 'filament.app.pages.bookkeeper.financial-statement-report';

    protected static ?string $navigationGroup = 'Bookkeeping';

    public function downloadTrialBalance()
    {
        return Action::make('downloadTrialBalance')
            ->action(function () {
                $loan_types = LoanType::get();
                $column = 'A';
                $loans_receivable_row = 12;
                $loans_interest_row = 88;
                $spreadsheet = IOFactory::load(storage_path('templates/trial_balance.xlsx'));
                $worksheet = $spreadsheet->getActiveSheet();
                $worksheet->insertNewRowBefore($loans_receivable_row, $loan_types->count());
                $loans_interest_row += $loan_types->count();
                $worksheet->insertNewRowBefore($loans_interest_row, $loan_types->count());
                $loan_types->each(function ($loan_type) use (&$worksheet, &$loans_receivable_row, &$column) {
                    $worksheet->setCellValue("$column$loans_receivable_row", strtoupper($loan_type->name));
                    $worksheet->getCell("$column$loans_receivable_row")->getStyle()->getFont()->setBold(false);
                    $loans_receivable_row++;
                });
                $loan_types->each(function ($loan_type) use (&$worksheet, &$loans_interest_row, &$column) {
                    $worksheet->setCellValue("$column$loans_interest_row", strtoupper($loan_type->name));
                    $worksheet->getCell("$column$loans_interest_row")->getStyle()->getFont()->setBold(false);
                    $loans_interest_row++;
                });
                $path = storage_path('app/livewire-tmp/trial_balance-' . today()->year . '.xlsx');
                $writer = IOFactory::createWriter($spreadsheet, 'Xlsx');
                $writer->save($path);
                return response()->download($path);
            });
    }
}
