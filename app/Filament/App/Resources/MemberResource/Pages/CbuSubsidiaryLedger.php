<?php

namespace App\Filament\App\Resources\MemberResource\Pages;

use App\Filament\App\Resources\MemberResource;
use App\Models\CapitalSubscriptionPayment;
use App\Models\Member;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Grid;
use Filament\Forms\Components\Select;
use Filament\Resources\Pages\Page;
use Filament\Tables\Actions\ViewAction;
use Filament\Tables\Columns\Summarizers\Sum;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Contracts\HasTable;
use Filament\Tables\Concerns\InteractsWithTable;
use Filament\Tables\Enums\FiltersLayout;
use Filament\Tables\Filters\Filter;
use Filament\Tables\Table;
use Illuminate\Contracts\Support\Htmlable;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\HtmlString;
use stdClass;

class CbuSubsidiaryLedger extends Page implements HasTable
{
    use InteractsWithTable;

    protected static string $resource = MemberResource::class;

    protected static string $view = 'filament.app.resources.member-resource.pages.cbu-subsidiary-ledger';

    public Member $member;

    public function getHeading(): string|Htmlable
    {
        return 'CBU Subsidiary Ledger for ' . $this->member->full_name;
    }

    public function table(Table $table): Table
    {
        return $table
            ->query(CapitalSubscriptionPayment::query()->whereRelation('capital_subscription', 'member_id', $this->member->id))
            ->columns([
                TextColumn::make('transaction_date')
                    ->date('m/d/Y')
                    ->label('DATE'),
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
                        return $table->getRecords()->takeUntil(fn (CapitalSubscriptionPayment $payment) => $payment->is($record))->sum('amount') + $record->amount;
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
