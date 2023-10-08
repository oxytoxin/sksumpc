<?php

namespace App\Filament\App\Resources\MemberResource\Pages;

use App\Models\Member;
use Filament\Tables\Table;
use Filament\Tables\Filters\Filter;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Contracts\HasTable;
use Filament\Tables\Enums\FiltersLayout;
use Filament\Forms\Components\DatePicker;
use App\Models\CapitalSubscriptionPayment;
use Filament\Tables\Columns\Summarizers\Sum;
use App\Filament\App\Resources\MemberResource;
use App\Models\LoanPayment;
use Filament\Resources\Pages\Page;
use Filament\Tables\Concerns\InteractsWithTable;
use Illuminate\Contracts\Support\Htmlable;
use Illuminate\Database\Eloquent\Builder;

class LoanSubsidiaryLedger extends Page implements HasTable
{
    use InteractsWithTable;

    protected static string $resource = MemberResource::class;

    protected static string $view = 'filament.app.resources.member-resource.pages.loan-subsidiary-ledger';

    public Member $member;

    public function getHeading(): string|Htmlable
    {
        return 'CBU Subsidiary Ledger for ' . $this->member->full_name;
    }

    public function table(Table $table): Table
    {
        return $table
            ->query(LoanPayment::query()->whereRelation('loan', 'member_id', $this->member->id))
            ->columns([
                TextColumn::make('transaction_date')
                    ->date('m/d/Y')
                    ->label('DATE'),
                TextColumn::make('loan.loan_type.name')
                    ->label('Loan Type'),
                TextColumn::make('reference_number')
                    ->label('REF.#'),
                TextColumn::make('dr')
                    ->label('DR'),
                TextColumn::make('amount')
                    ->label('CR')
                    ->money('PHP')
                    ->summarize(Sum::make()->label('')->money('PHP')),
                TextColumn::make('ob')
                    ->label('Outstanding Balance')
                    ->state(function (Table $table, $record) {
                        return $table->getRecords()->takeUntil(fn (LoanPayment $payment) => $payment->is($record))->sum('amount') + $record->amount;
                    })
                    ->money('PHP'),
                TextColumn::make('remarks'),
            ])
            ->filters([
                Filter::make('transaction_date')
                    ->form([
                        DatePicker::make('from')
                            ->default(today()->subYear()),
                        DatePicker::make('to')
                            ->default(today()),
                    ])
                    ->columns(8)
                    ->columnSpanFull()
                    ->query(fn (Builder $query, array $data) => $query->whereDate('transaction_date', '>=', $data['from'])->whereDate('transaction_date', '<=', $data['to']))
            ])
            ->filtersLayout(FiltersLayout::AboveContent)
            ->paginated(['all']);
    }
}
