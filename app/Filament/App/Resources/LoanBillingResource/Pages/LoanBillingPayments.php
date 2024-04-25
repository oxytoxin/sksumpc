<?php

namespace App\Filament\App\Resources\LoanBillingResource\Pages;

use App\Filament\App\Resources\LoanBillingResource;
use App\Models\LoanBilling;
use App\Models\LoanBillingPayment;
use App\Models\MemberType;
use DB;
use Filament\Actions\Action;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\TextInput;
use Filament\Notifications\Notification;
use Filament\Resources\Pages\ListRecords;
use Filament\Tables\Actions\DeleteAction;
use Filament\Tables\Actions\EditAction;
use Filament\Tables\Columns\Summarizers\Sum;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Enums\FiltersLayout;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;
use Illuminate\Contracts\Support\Htmlable;
use PhpOffice\PhpSpreadsheet\IOFactory;
use Spatie\SimpleExcel\SimpleExcelReader;

class LoanBillingPayments extends ListRecords
{
    protected static string $resource = LoanBillingResource::class;

    public LoanBilling $loan_billing;

    public function getHeading(): string|Htmlable
    {
        return 'Loan Billing Receivables';
    }

    protected function getHeaderActions(): array
    {
        return [
            Action::make('Import')
                ->visible(auth()->user()->can('manage loans'))
                ->form([
                    FileUpload::make('billing')
                        ->storeFiles(false)
                        ->acceptedFileTypes(['application/vnd.openxmlformats-officedocument.spreadsheetml.sheet', 'application/octet-stream']),
                ])
                ->disabled(fn () => $this->loan_billing->posted)
                ->action(function ($data) {
                    $payments = $this->loan_billing->loan_billing_payments()->join('members', 'loan_billing_payments.member_id', 'members.id')
                        ->selectRaw('loan_billing_payments.*, members.mpc_code as member_code')
                        ->get();
                    $rows = SimpleExcelReader::create($data['billing']->getRealPath(), type: 'xlsx')
                        ->headerOnRow(1)
                        ->getRows()
                        ->collect();
                    DB::beginTransaction();
                    $payments->each(function ($payment) use ($rows) {
                        $rowData = $rows->firstWhere('MEMBER ID', $payment->member_code);
                        if ($rowData) {
                            $payment->update([
                                'amount_paid' => $rowData['AMOUNT PAID'],
                            ]);
                        }
                    });
                    DB::commit();
                    Notification::make()->title('Amount paid values updated!')->success()->send();
                })
                ->color('success'),
            Action::make('Export')
                ->visible(auth()->user()->can('manage loans'))
                ->action(function () {
                    $title = str('SKSU MPC - ')->append($this->loan_billing->loan_type->name)->append(' - as of ')->append($this->loan_billing->date->format('F Y'))->upper();
                    $filename = $title->append('.xlsx');
                    $loan_billing_payments = LoanBillingPayment::whereBelongsTo($this->loan_billing, 'loan_billing')
                        ->join('members', 'loan_billing_payments.member_id', 'members.id')
                        ->selectRaw('loan_billing_payments.*, members.alt_full_name as member_name, members.mpc_code as member_code')
                        ->orderBy('member_name')
                        ->get();
                    $spreadsheet = IOFactory::load(storage_path('templates/billing_template.xlsx'));
                    $worksheet = $spreadsheet->getActiveSheet();
                    $worksheet->setCellValue('A1', $title);
                    $worksheet->insertNewRowBefore(3, $loan_billing_payments->count());
                    foreach ($loan_billing_payments as $key => $payment) {
                        $worksheet->setCellValue('A' . $key + 3, $key + 1);
                        $worksheet->setCellValue('B' . $key + 3, $payment->member_code);
                        $worksheet->setCellValue('C' . $key + 3, $payment->member_name);
                        $worksheet->setCellValue('D' . $key + 3, $payment->amount_due);
                        $worksheet->setCellValue('E' . $key + 3, $payment->amount_paid);
                    }
                    $worksheet->getProtection()->setSheet(true)->setInsertRows(true)->setInsertColumns(true);
                    $worksheet->protectCells('E3:E' . ($loan_billing_payments->count() + 2), auth()->user()->getAuthPassword(), true);
                    $path = storage_path('app/livewire-tmp/' . $filename);
                    $writer = IOFactory::createWriter($spreadsheet, 'Xlsx');
                    $writer->save($path);

                    return response()->download($path);
                })
                ->color('success'),

            Action::make('Back to previous page')
                ->extraAttributes(['wire:ignore' => true])
                ->url(back()->getTargetUrl()),
        ];
    }

    public function table(Table $table): Table
    {
        return $table
            ->query(
                LoanBillingPayment::where('loan_billing_id', $this->loan_billing->id)->join('members', 'loan_billing_payments.member_id', 'members.id')
                    ->selectRaw('loan_billing_payments.*, members.alt_full_name as member_name')
                    ->orderBy('member_name')
            )
            ->columns([
                TextColumn::make('member_name')->label('Member'),
                TextColumn::make('amount_due')->money('PHP'),
                TextColumn::make('amount_paid')->money('PHP'),
            ])
            ->filters([
                SelectFilter::make('member.member_type_id')
                    ->relationship('member.member_type', 'name')
                    ->query(fn ($query, $livewire) => $query->when($livewire->tableFilters['member']['member_type_id']['value'] ?? null, fn ($q, $v) => $q->whereRelation('member', 'member_type_id', $v)))
            ])
            ->filtersLayout(FiltersLayout::AboveContent)
            ->actions([
                EditAction::make()
                    ->form([
                        TextInput::make('amount_paid')
                            ->default(fn ($record) => $record->amount_paid)
                            ->moneymask(),
                    ])
                    ->visible(fn ($record) => !$record->posted && auth()->user()->can('manage loans')),
                DeleteAction::make()
                    ->visible(fn ($record) => !$record->posted && auth()->user()->can('manage loans')),
            ]);
    }
}
