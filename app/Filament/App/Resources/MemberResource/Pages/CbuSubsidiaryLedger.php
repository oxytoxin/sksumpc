<?php

namespace App\Filament\App\Resources\MemberResource\Pages;

use App\Models\User;
use App\Models\Member;
use Filament\Tables\Table;
use Filament\Resources\Pages\Page;
use Filament\Forms\Components\Grid;
use Filament\Tables\Actions\Action;
use Filament\Tables\Filters\Filter;
use Filament\Forms\Components\Select;
use Filament\Tables\Actions\ViewAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Contracts\HasTable;
use Filament\Tables\Enums\FiltersLayout;
use Filament\Forms\Components\DatePicker;
use Illuminate\Database\Eloquent\Builder;
use App\Models\CapitalSubscriptionPayment;
use Illuminate\Contracts\Support\Htmlable;
use Filament\Tables\Columns\Summarizers\Sum;
use App\Filament\App\Resources\MemberResource;
use Filament\Tables\Concerns\InteractsWithTable;
use App\Filament\App\Pages\Cashier\Reports\HasSignatories;


class CbuSubsidiaryLedger extends Page implements HasTable
{
    use InteractsWithTable, HasSignatories;

    protected static string $resource = MemberResource::class;

    protected static string $view = 'filament.app.resources.member-resource.pages.cbu-subsidiary-ledger';

    public Member $member;

    public function getHeading(): string|Htmlable
    {
        return 'CBU Subsidiary Ledger for ' . $this->member->full_name;
    }

    protected function getSignatories()
    {
        $manager = User::whereRelation('roles', 'name', 'manager')->first();
        $this->signatories = [
            [
                'action' => 'Prepared by:',
                'name' => auth()->user()->name,
                'position' => 'Teller/Cashier'
            ],
            [
                'action' => 'Noted:',
                'name' => $manager?->name ?? 'FLORA C. DAMANDAMAN',
                'position' => 'Manager'
            ],
        ];
    }

    public function table(Table $table): Table
    {
        return $table
            ->query(CapitalSubscriptionPayment::query()->whereRelation('capital_subscription', 'member_id', $this->member->id))
            ->content(fn () => view('filament.app.views.cbu-sl', ['member' => $this->member, 'signatories' => $this->signatories]))
            ->columns([
                TextColumn::make('transaction_date')
                    ->date('m/d/Y')
                    ->label('DATE'),
                TextColumn::make('reference_number')
                    ->label('REF.#'),
                TextColumn::make('dr')
                    ->label('DR'),
                TextColumn::make('amount')
                    ->label('CR'),
                TextColumn::make('ob')
                    ->label('Outstanding Balance')
                    ->state(function (Table $table, $record) {
                        return $table->getRecords()->takeUntil(fn (CapitalSubscriptionPayment $payment) => $payment->is($record))->sum('amount') + $record->amount;
                    }),
                TextColumn::make('remarks'),
            ])
            ->filters([
                Filter::make('transaction_date')
                    ->form([
                        DatePicker::make('from')
                            ->default(today()->startOfYear()),
                        DatePicker::make('to')
                            ->default(today()),
                    ])
                    ->columns(8)
                    ->columnSpanFull()
                    ->query(fn (Builder $query, array $data) => $query->whereDate('transaction_date', '>=', $data['from'])->whereDate('transaction_date', '<=', $data['to']))
            ])
            ->filtersLayout(FiltersLayout::AboveContent)
            ->headerActions([
                Action::make('back')
                    ->label('Back to CBU')
                    ->url(route('filament.app.resources.members.view', ['record' => $this->member, 'tab' => '-cbu-tab']))
            ])
            ->paginated(['all']);
    }
}
