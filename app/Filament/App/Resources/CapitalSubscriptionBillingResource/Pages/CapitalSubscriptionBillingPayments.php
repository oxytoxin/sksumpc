<?php

namespace App\Filament\App\Resources\CapitalSubscriptionBillingResource\Pages;

use App\Filament\App\Resources\CapitalSubscriptionBillingResource;
use App\Models\CapitalSubscriptionBilling;
use App\Models\CapitalSubscriptionBillingPayment;
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
use Illuminate\Support\Facades\DB;
use PhpOffice\PhpSpreadsheet\IOFactory;
use Spatie\SimpleExcel\SimpleExcelReader;

class CapitalSubscriptionBillingPayments extends ListRecords
{
    protected static string $resource = CapitalSubscriptionBillingResource::class;

    protected static string $view = 'filament.app.resources.capital-subscription-billing-resource.pages.capital-subscription-billing-payments';

    public CapitalSubscriptionBilling $capital_subscription_billing;

    public function getHeading(): string|Htmlable
    {
        return 'CBU Receivables';
    }

    protected function getHeaderActions(): array
    {
        return [
            Action::make('Import')
                ->visible(auth()->user()->can('manage cbu'))
                ->form([
                    FileUpload::make('billing')
                        ->storeFiles(false)
                        ->acceptedFileTypes(['application/vnd.openxmlformats-officedocument.spreadsheetml.sheet', 'application/octet-stream']),
                ])
                ->disabled(fn () => $this->capital_subscription_billing->posted)
                ->action(function ($data) {
                    $payments = $this->capital_subscription_billing->capital_subscription_billing_payments()->join('members', 'capital_subscription_billing_payments.member_id', 'members.id')
                        ->selectRaw('capital_subscription_billing_payments.*, members.mpc_code as member_code')
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
                ->visible(auth()->user()->can('manage cbu'))
                ->action(function () {
                    $title = str('SKSU MPC CBU BILLING')->append(' - as of ')->append($this->capital_subscription_billing->date->format('F Y'))->upper();
                    $filename = $title->append('.xlsx');
                    $capital_subscription_billing_payments = CapitalSubscriptionBillingPayment::whereBelongsTo($this->capital_subscription_billing, 'capital_subscription_billing')
                        ->join('members', 'capital_subscription_billing_payments.member_id', 'members.id')
                        ->selectRaw('capital_subscription_billing_payments.*, members.alt_full_name as member_name, members.mpc_code as member_code')
                        ->orderBy('member_name')
                        ->get();
                    $spreadsheet = IOFactory::load(storage_path('templates/billing_template.xlsx'));
                    $worksheet = $spreadsheet->getActiveSheet();
                    $worksheet->setCellValue('A1', $title);
                    $worksheet->insertNewRowBefore(3, $capital_subscription_billing_payments->count());
                    foreach ($capital_subscription_billing_payments as $key => $payment) {
                        $worksheet->setCellValue('A' . $key + 3, $key + 1);
                        $worksheet->setCellValue('B' . $key + 3, $payment->member_code);
                        $worksheet->setCellValue('C' . $key + 3, $payment->member_name);
                        $worksheet->setCellValue('D' . $key + 3, $payment->amount_due);
                        $worksheet->setCellValue('E' . $key + 3, $payment->amount_paid);
                    }
                    $worksheet->getProtection()->setSheet(true)->setInsertRows(true)->setInsertColumns(true);
                    $worksheet->protectCells('E3:E' . ($capital_subscription_billing_payments->count() + 2), auth()->user()->getAuthPassword(), true);
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
                CapitalSubscriptionBillingPayment::query()
                    ->where('capital_subscription_billing_id', $this->capital_subscription_billing->id)
                    ->join('members', 'capital_subscription_billing_payments.member_id', 'members.id')
                    ->selectRaw('capital_subscription_billing_payments.*, members.alt_full_name as member_name')
                    ->orderBy('member_name')
            )
            ->columns([
                TextColumn::make('member.alt_full_name')->label('Member')->searchable(),
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
                    ->visible(fn ($record) => !$record->posted),
                DeleteAction::make()
                    ->visible(fn ($record) => !$record->posted),
            ]);
    }
}
