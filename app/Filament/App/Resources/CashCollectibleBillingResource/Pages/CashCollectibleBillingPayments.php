<?php

namespace App\Filament\App\Resources\CashCollectibleBillingResource\Pages;

use Filament\Actions;
use Filament\Tables\Table;
use Illuminate\Support\Facades\DB;
use App\Models\CashCollectibleBilling;
use Filament\Tables\Actions\EditAction;
use Filament\Tables\Columns\TextColumn;
use PhpOffice\PhpSpreadsheet\IOFactory;
use Filament\Forms\Components\TextInput;
use Filament\Notifications\Notification;
use Filament\Tables\Enums\FiltersLayout;
use Filament\Forms\Components\FileUpload;
use Filament\Resources\Pages\ListRecords;
use Filament\Tables\Actions\DeleteAction;
use Filament\Tables\Filters\SelectFilter;
use Spatie\SimpleExcel\SimpleExcelReader;
use Illuminate\Contracts\Support\Htmlable;
use Filament\Tables\Columns\Summarizers\Sum;
use App\Models\CashCollectibleBillingPayment;
use App\Models\CapitalSubscriptionBillingPayment;
use App\Filament\App\Resources\CashCollectibleBillingResource;
use App\Models\Member;
use Filament\Actions\Action;
use Filament\Actions\CreateAction;
use Filament\Forms\Components\Select;

class CashCollectibleBillingPayments extends ListRecords
{
    protected static string $resource = CashCollectibleBillingResource::class;

    public CashCollectibleBilling $cash_collectible_billing;

    public function getHeading(): string|Htmlable
    {
        return 'Cash Collectible Receivables';
    }

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make()->label('New Cash Collectible Receivable')
                ->createAnother(false)
                ->form([
                    Select::make('member_id')
                        ->label('Member')
                        ->options(Member::pluck('full_name', 'id'))
                        ->searchable()
                        ->required()
                        ->reactive()
                        ->afterStateUpdated(fn ($set, $state) => $set('payee', Member::find($state)?->full_name))
                        ->preload(),
                    TextInput::make('payee')
                        ->required(),
                    TextInput::make('amount')
                        ->moneymask(),
                ])
                ->action(function ($data) {
                    CashCollectibleBillingPayment::create([
                        'cash_collectible_billing_id' => $this->cash_collectible_billing->id,
                        'cash_collectible_id' => $this->cash_collectible_billing->cash_collectible_id,
                        'member_id' => $data['member_id'],
                        'payee' => $data['payee'],
                        'amount_due' => $data['amount'],
                        'amount_paid' => $data['amount'],
                    ]);
                    Notification::make()->title('New receivable created!')->success()->send();
                }),
            Action::make('Import')
                ->visible(auth()->user()->can('manage cbu'))
                ->form([
                    FileUpload::make('billing')
                        ->storeFiles(false)
                        ->acceptedFileTypes(['application/vnd.openxmlformats-officedocument.spreadsheetml.sheet', 'application/octet-stream']),
                ])
                ->disabled(fn () => $this->cash_collectible_billing->posted)
                ->action(function ($data) {
                    $payments = $this->cash_collectible_billing->cash_collectible_billing_payments()->join('members', 'cash_collectible_billing_payments.member_id', 'members.id')
                        ->selectRaw('cash_collectible_billing_payments.*, members.mpc_code as member_code')
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
                    $title = str('SKSU MPC CBU BILLING')->append(' - as of ')->append($this->cash_collectible_billing->date->format('F Y'))->upper();
                    $filename = $title->append('.xlsx');
                    $cash_collectible_billing_payments = CashCollectibleBillingPayment::whereBelongsTo($this->cash_collectible_billing, 'cash_collectible_billing')
                        ->join('members', 'cash_collectible_billing_payments.member_id', 'members.id')
                        ->selectRaw('cash_collectible_billing_payments.*, members.alt_full_name as member_name, members.mpc_code as member_code')
                        ->orderBy('member_name')
                        ->get();
                    $spreadsheet = IOFactory::load(storage_path('templates/billing_template.xlsx'));
                    $worksheet = $spreadsheet->getActiveSheet();
                    $worksheet->setCellValue('A1', $title);
                    $worksheet->insertNewRowBefore(3, $cash_collectible_billing_payments->count());
                    foreach ($cash_collectible_billing_payments as $key => $payment) {
                        $worksheet->setCellValue('A' . $key + 3, $key + 1);
                        $worksheet->setCellValue('B' . $key + 3, $payment->member_code);
                        $worksheet->setCellValue('C' . $key + 3, $payment->member_name);
                        $worksheet->setCellValue('D' . $key + 3, $payment->amount_due);
                        $worksheet->setCellValue('E' . $key + 3, $payment->amount_paid);
                    }
                    $worksheet->getProtection()->setSheet(true)->setInsertRows(true)->setInsertColumns(true);
                    $worksheet->protectCells('E3:E' . ($cash_collectible_billing_payments->count() + 2), auth()->user()->getAuthPassword(), true);
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
                CashCollectibleBillingPayment::query()
                    ->where('cash_collectible_billing_id', $this->cash_collectible_billing->id)
                    ->join('members', 'cash_collectible_billing_payments.member_id', 'members.id')
                    ->selectRaw('cash_collectible_billing_payments.*, members.alt_full_name as member_name')
                    ->orderBy('member_name')
            )
            ->emptyStateHeading('No cash collectible receivables')
            ->columns([
                TextColumn::make('member.alt_full_name')->label('Member')->searchable(),
                TextColumn::make('cash_collectible.name'),
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